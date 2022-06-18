<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Services\TransferHistory;
use App\Services\VerifyAccount;
use App\Traits\ApiResponse;

class PaymentController extends Controller
{

    public function index()
    {
        $payments = Payment::get();
        $paymentsResource = PaymentResource::collection($payments);
        return ApiResponse::successResponseWithData($paymentsResource, 'Payments retrieved', 200);
    }

    public static function initiateTransfer($data, VerifyAccount $act){
        return $data;
        $data = [
            'account_number' => $data['account_number'],
            'bank_code' => $data['bank_code'],
            'amount' => $data['amount'],
            'reason' => 'Rana Withdrawal',
        ];
		return $act->execute($data);
	}
}
