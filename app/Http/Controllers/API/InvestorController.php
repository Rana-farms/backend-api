<?php

namespace App\Http\Controllers\API;
use App\Repositories\RepositoryInterfaces\InvestorRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\InvestmentResource;
use App\Http\Resources\UserResource;
use App\Models\Document;
use App\Models\Investment;
use Illuminate\Support\Carbon;
use App\Models\ROIHistory;
use App\Models\User;
use App\Models\UserInvestment;
use App\Traits\ApiResponse;

class InvestorController extends Controller
{
    public function __construct(InvestorRepositoryInterface $investorRepository)
    {
    $this->investorRepository = $investorRepository;
    $this->walletController = new WalletController();
    }

    public function index()
    {
        $investors = User::whereRoleId('9')->get();
        $investorsResource = UserResource::collection($investors);
        return ApiResponse::successResponseWithData($investorsResource, 'Investors retrieved', 200);
    }

    public function getDashboardAnalytics()
    {
        $userId = auth()->user()->id;
        $startOfMonth = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString() . ' ' . '23:59:59';
        $startOfTwoMonths = Carbon::now()->subMonth(2)->startOfMonth()->toDateString();
        $endOfTwoMonths = Carbon::now()->subMonth(2)->endOfMonth()->toDateString() . ' ' . '23:59:59';
        $date = Carbon::now();
        $startOfYear = $date->startOfYear()->toDateString();
        $endOfYear = $date->endOfYear()->toDateString() . ' ' . '23:59:59';
        $revenueLastMonth = ROIHistory::whereUserId($userId)->whereBetween('created_at',[$startOfMonth, $endOfMonth])->get(['amount']);
        $revenueLastTwoMonths = ROIHistory::whereUserId($userId)->whereBetween('created_at',[$startOfTwoMonths, $endOfTwoMonths])->get(['amount']);
        $walletBalance = $this->walletController->getInvestorBalance($userId);
        $capitalBalance = $this->getActiveInvestments($userId);
        $allRevenueEarnedThisYear = ROIHistory::whereUserId($userId)->whereBetween('created_at',[$startOfYear, $endOfYear])->get(['amount']);
        $investments = Investment::get();
        $investmentsResource = InvestmentResource::collection($investments);
        $documents = Document::get();
        $documentsResource = DocumentResource::collection($documents);

        if( count($allRevenueEarnedThisYear) > 0){
            $netIncome = $allRevenueEarnedThisYear->sum('amount');
        } else{
            $netIncome = 0;
        }

        if( count($revenueLastMonth) > 0){
            $roiForLastMonth = $revenueLastMonth->sum('amount');
        } else{
            $roiForLastMonth = 0;
        }

        if( count($revenueLastTwoMonths) > 0){
            $roiForLastTwoMonths = $revenueLastTwoMonths->sum('amount');
        } else{
            $roiForLastTwoMonths = 0;
        }

        if($roiForLastTwoMonths != 0){
            $subtractBothRoi = $roiForLastMonth - $roiForLastTwoMonths;
            $percentageSinceLastMonths = round( $subtractBothRoi / $roiForLastTwoMonths * 100, 2 );
        } else{
            $percentageSinceLastMonths = null;
        }

        if($capitalBalance != 0 && $netIncome != 0){
            $monthlyNetIncome =  round( $netIncome / $capitalBalance * 100, 2 );
        } else{
            $monthlyNetIncome = 0;
        }

        $data = [
            'monthlyRoi' => $roiForLastMonth,
            'percentageSinceLastMonths' => $percentageSinceLastMonths,
            'netIncome' => $netIncome,
            'monthlyNetIncome' => $monthlyNetIncome,
            'availableFunds' => $walletBalance,
            'capitalBalance' => $capitalBalance,
            'investments' => $investmentsResource,
            'documents' => $documentsResource
        ];
        return ApiResponse::successResponseWithData($data, 'Dashboard metrics retrieved', 200);
    }

    public function getActiveInvestments($investorId)
    {
        $today = Carbon::today()->toDateString() . ' ' . '23:59:59';
        $investments = UserInvestment::whereUserId($investorId)->whereStatus(1)->whereDate('end_date', '>=', $today)->get(['amount']);
        if( $investments ){
            $capitalBalance = $investments->sum('amount');
        }
        return $investments ? $capitalBalance : 0;
    }

    public function getDashboardGraph()
    {
        $userId = auth()->user()->id;
        $date = Carbon::now();
        $startOfYear = $date->startOfYear()->toDateString();
        $endOfYear = $date->endOfYear()->toDateString() . ' ' . '23:59:59';
        $yearlyRoi = ROIHistory::selectRaw('monthname(created_at) as month, sum(amount) as total')
            ->whereUserId($userId)->whereBetween('created_at',[$startOfYear, $endOfYear])
            ->groupBy('month')
            ->orderByRaw('min(created_at) desc')
            ->get();

        return ApiResponse::successResponseWithData($yearlyRoi, 'Dashboard graph retrieved', 200);
    }
}


