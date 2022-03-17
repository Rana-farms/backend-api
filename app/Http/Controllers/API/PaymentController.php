<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\UserInvestmentController;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessInvestmentPaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Investment;
use App\Models\Payment;
use App\Models\UserInvestment;
use App\Traits\ApiResponse;

class PaymentController extends Controller
{
    public function __construct()
    {
    $this->userInvestmentController = new UserInvestmentController();
    }

    public function index()
    {
        //
    }

    public function processInvestmentPayment(ProcessInvestmentPaymentRequest $request)
    {
        $data = $request->validated();
        $userId = auth()->user()->id;
        $userInvestment = UserInvestment::find( $data['investment_id'] );
        $investmentUserId = $userInvestment->user_id;
        $getInvestment = Investment::find( $userInvestment->investment_id );
        $expectedAmount = $userInvestment->amount;

        if( ! $investmentUserId === $userId ){
            return ApiResponse::errorResponse('You are unauthorized to edit this resource', 403);
        }

        if( $data['amount'] < $expectedAmount ){
            return ApiResponse::errorResponse('Amount is below expected amount', 403);
        }

        if( $userInvestment->status == 1 ){
            return ApiResponse::errorResponse('Payment has been made for this investment', 200);
        }

        $data['user_id'] = $userId;
        $data['status'] = 1;
        $payment = Payment::create( $data );
        $paymentResource = new PaymentResource( $payment );

        $data['payment_id'] = $payment->id;
        $data['period'] = $getInvestment->lock_up_period;

        $updateInvestmentStatus = $this->userInvestmentController->update( $data );

        return ApiResponse::successResponseWithData( $paymentResource, 'Payment processed successfully', 200);

    }

    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }
}
