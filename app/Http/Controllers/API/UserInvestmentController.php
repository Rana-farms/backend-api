<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserInvestmentRequest;
use App\Http\Resources\UserInvestmentResource;
use App\Models\Investment;
use App\Models\UserInvestment;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class UserInvestmentController extends Controller
{

    public function __construct(){
        $this->secret_key = config('app.secretKey');
    }

    public function index()
    {
        $userId = auth()->user()->id;
        $userInvestments = UserInvestment::where('user_id', $userId)->get();
        $userInvestmentsResource = UserInvestmentResource::collection($userInvestments);
        return ApiResponse::successResponseWithData($userInvestmentsResource, 'Investments retrieved', 203);
    }


    public function store(CreateUserInvestmentRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();
        $data['user_id'] = $user->id;
        $getInvestment = Investment::find($data['investment_id']);
        $minimumUnits = $getInvestment->minimum_unit;
        $pricePerUnit = $getInvestment->unit_price;
        $period = $getInvestment->lock_up_period;
        $expectedAmount = $pricePerUnit * $data['units'];
        $validPaymentReference = $this->verifyPayment( $data['payment_reference'] );

        if( $user->verified !== 1 ){
            return ApiResponse::errorResponse( 'Your profile isn\'t verified, so you can\'t make an investment at the moment', 403 );
        }

        if( ! $validPaymentReference ){
            return ApiResponse::errorResponse( 'Invalid payment reference', 403 );
        }

        if( $data['amount'] < $expectedAmount ){
            return ApiResponse::errorResponse('Amount is below expected amount', 403);
        }

        if ( $data['units'] < $minimumUnits ) {
            return ApiResponse::errorResponse( 'Unit is below the required minimum unit for the selected investment', 400);
        }

        $data['amount'] = $expectedAmount;
        $data['is_paid'] = 1;
        $data['status'] = 1;
        $data['start_date'] = Carbon::now();
        $data['end_date'] = Carbon::now()->addMonths( $period );

        $userInvestment = UserInvestment::create( $data );
        $userInvestmentResource = new UserInvestmentResource( $userInvestment );
        return ApiResponse::successResponseWithData( $userInvestmentResource, 'Investment created', 203 );

    }


    public function show(UserInvestment $investment)
    {
        $userId = auth()->user()->id;
        $investmentUserId = $investment->user_id;
        if ($investmentUserId === $userId) {
            $investmentResource = new UserInvestmentResource($investment);
            return ApiResponse::successResponseWithData($investmentResource, 'Investment retrieved', 200);
        } else {
            return ApiResponse::errorResponse('You are unauthorized to view this resource', 403);
        }
    }

    public function destroy(UserInvestment $investment)
    {
        $userId = auth()->user()->id;
        $investmentUserId = $investment->user_id;
        if ($investmentUserId === $userId) {
            if (!empty($investment->is_paid)) {
                $investment->delete();
                return ApiResponse::successResponse('Investment deleted', 200);
            } else {
                return ApiResponse::errorResponse('You can not delete this investment', 403);
            }
        } else {
            return ApiResponse::errorResponse('You are unauthorized to view this resource', 403);
        }
    }

    public function verifyPayment(string $reference)
    {
        $response = Http::acceptJson()
                            ->withHeaders([
                                'Authorization' => "Bearer $this->secret_key",
                            ])
                            ->get('https://api.paystack.co/transaction/verify/' . $reference);
                            return $response['status'];
    }
}

