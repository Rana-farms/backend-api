<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Services\TransferHistory;
use App\Services\VerifyAccount;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function index()
    {
        $payments = Payment::get();
        $paymentsResource = PaymentResource::collection($payments);
        return ApiResponse::successResponseWithData($paymentsResource, 'Payments retrieved', 200);
    }

    public static function initiateTransfer(Request $request, VerifyAccount $act){

        $data = [
            'account_number' => '',
            'bank_code' => '',
            'amount' => '',
            'reason' => 'Rana Withdrawal',
        ];
		return $act->execute($data);
	}
}
