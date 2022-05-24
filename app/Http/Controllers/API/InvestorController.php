<?php

namespace App\Http\Controllers\API;
use App\Repositories\RepositoryInterfaces\InvestorRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateInvestorProfile;
use App\Http\Resources\UserResource;
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

    public function profile()
    {
        $userID = auth()->user()->id;
        $user = User::find( $userID );
        $userResource = new UserResource( $user );
        $userResource->load( 'nextOfKin', 'bank', 'wallet' );
        return ApiResponse::successResponseWithData( $userResource, 'Investor profile', 200 );
    }

    public function update( UpdateInvestorProfile $request )
    {
        $userId = auth()->user()->id;
        $user = User::find( $userId );
        if( $user ){
        $data = $request->validated();

        if ($request->hasFile('identity_document')) {
            $data['identity_document'] = $request->file('identity_document')->store('identity');
          }

        $updateProfile = $user->update( $data );
        $userResource = new UserResource( $user );
        $userResource->load( 'nextOfKin', 'bank', 'wallet' );
        return ApiResponse::successResponseWithData( $userResource, 'Profile updated successfully', 200 );
        } else{
            return ApiResponse::errorResponse( 'Profile not found', 404);
        }

    }

    public function getDashboardAnalytics()
    {
        $userId = auth()->user()->id;
        $startOfMonth = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString() . ' ' . '23:59:59';
        $date = Carbon::now();
        $startOfYear = $date->startOfYear()->toDateString();
        $endOfYear = $date->endOfYear()->toDateString() . ' ' . '23:59:59';
        $revenueLastMonth = ROIHistory::whereUserId($userId)->whereBetween('created_at',[$startOfMonth, $endOfMonth])->get(['amount']);
        $walletBalance = $this->walletController->getInvestorBalance($userId);
        $capitalBalance = $this->getActiveInvestments($userId);
        $allRevenueEarnedThisYear = ROIHistory::whereUserId($userId)->whereBetween('created_at',[$startOfYear, $endOfYear])->get(['amount']);
        
        if( count($allRevenueEarnedThisYear) > 0){
            $netIncome = $allRevenueEarnedThisYear->sum('amount');;
        } else{
            $netIncome = 0;
        }

        if( count($revenueLastMonth) > 0){
            $roiForLastMonth = $revenueLastMonth->sum('amount');
        } else{
            $roiForLastMonth = 0;
        }

        $data = [
            'monthlyRoi' => $roiForLastMonth,
            'percentageSinceLastMonths' => 10,
            'netIncome' => $netIncome,
            'availableFunds' => $walletBalance,
            'capitalBalance' => $capitalBalance,
        ];
        return ApiResponse::successResponseWithData($data, 'Dashboard metrics retrieved', 200);
    }

    public function getActiveInvestments($investorId)
    {
        $today = Carbon::today()->toDateString() . ' ' . '23:59:59';
        $investments = UserInvestment::whereUserId($investorId)->whereStatus(0)->whereDate('end_date', '>=', $today)->get(['amount']);
        if( $investments ){
            $capitalBalance = $investments->sum('amount');
        } else{
            $capitalBalance = 0;
        }
        return $capitalBalance;
    }
}


