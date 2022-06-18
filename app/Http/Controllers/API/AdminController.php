<?php

namespace App\Http\Controllers\API;
use App\Repositories\RepositoryInterfaces\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRoleRequest;
use App\Http\Requests\InviteAdminRequest;
use App\Http\Requests\RemoveAdminRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Carbon;
use App\Models\ROIHistory;
use App\Http\Resources\InvestmentResource;
use App\Models\Investment;
use App\Models\Document;
use App\Http\Resources\DocumentResource;
use App\Notifications\AdminInviteNotification;
use App\Models\UserInvestment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use App\Traits\ApiResponse;

class AdminController extends Controller
{
    public function __construct(UserRepositoryInterface $userRepository

    ){
        $this->userRepository = $userRepository;
        $this->walletController = new WalletController();
    }

    public function index()
    {
        $admins = User::whereIn('role_id', [1,18])->get();
        $adminsResource = UserResource::collection($admins);
        return ApiResponse::successResponseWithData($adminsResource, 'Admins retrieved', 200);
    }

    public function store(InviteAdminRequest $request)
    {
        $adminData = $request->validated();

        $adminExist = User::whereRoleId(1)->first();
        if( $adminExist ){
            return ApiResponse::errorResponse('Admin admin already exists', 400);
        }

        $adminData['role_id'] = 1;
        $password = Str::random(6);
        $adminData['password'] = Hash::make($password);
        $adminData['email_verified_at'] = now();
        $newUser = $this->userRepository->create( $adminData );
        $adminResource = new UserResource($newUser);

        Notification::route('mail', $newUser->email )->notify( (new AdminInviteNotification( $newUser, $password )) );
        return ApiResponse::successResponseWithData($adminResource, 'Admin added!', 201);
    }

    public function updateRole(AdminRoleRequest $request)
    {
        $user = User::find($request->user_id);
        $role = $request['role'] == 'super-admin' ? 18 : 1;
        $user->update(['role_id' => $role]);
        $userResource = new UserResource($user);
        return ApiResponse::successResponseWithData($userResource, 'Admin role updated!', 200);
    }

    public function delete(RemoveAdminRequest $request)
    {
        $user = User::find($request->user_id);
        $user->delete();
        return ApiResponse::successResponse('Admin deleted', 200);
    }

    public function getDashboardAnalytics()
    {
        $startOfMonth = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString() . ' ' . '23:59:59';
        $startOfTwoMonths = Carbon::now()->subMonth(2)->startOfMonth()->toDateString();
        $endOfTwoMonths = Carbon::now()->subMonth(2)->endOfMonth()->toDateString() . ' ' . '23:59:59';
        $date = Carbon::now();
        $startOfYear = $date->startOfYear()->toDateString();
        $endOfYear = $date->endOfYear()->toDateString() . ' ' . '23:59:59';
        $revenueLastMonth = ROIHistory::whereBetween('created_at',[$startOfMonth, $endOfMonth])->get(['amount']);
        $revenueLastTwoMonths = ROIHistory::whereBetween('created_at',[$startOfTwoMonths, $endOfTwoMonths])->get(['amount']);
        $walletBalance = $this->walletController->getAllInvestorsWalletBalance();
        $capitalBalance = $this->getActiveInvestments();
        $allRevenueEarnedThisYear = ROIHistory::whereBetween('created_at',[$startOfYear, $endOfYear])->get(['amount']);
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

    public function getActiveInvestments()
    {
        $today = Carbon::today()->toDateString() . ' ' . '23:59:59';
        $investments = UserInvestment::whereStatus(1)->whereDate('end_date', '>=', $today)->get(['amount']);
        if( $investments ){
            $capitalBalance = $investments->sum('amount');
        }
        return $investments ? $capitalBalance : 0;
    }

    public function getDashboardGraph()
    {
        $date = Carbon::now();
        $startOfYear = $date->startOfYear()->toDateString();
        $endOfYear = $date->endOfYear()->toDateString() . ' ' . '23:59:59';
        $yearlyRoi = ROIHistory::selectRaw('monthname(created_at) as month, sum(amount) as total')
            ->whereBetween('created_at',[$startOfYear, $endOfYear])
            ->groupBy('month')
            ->orderByRaw('min(created_at) desc')
            ->get();

        return ApiResponse::successResponseWithData($yearlyRoi, 'Dashboard graph retrieved', 200);
    }
}
