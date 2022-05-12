<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateWithdrawalRequest;
use App\Http\Resources\WithdrawalResource;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Http\Controllers\API\TransactionController;
use App\Traits\ApiResponse;

class WithdrawalController extends Controller
{
    public function __construct(){
        $this->transactionController = new TransactionController();
    }

    public function index()
    {
        $userId = auth()->user()->id;
        $withdrawals = Withdrawal::where( 'user_id', $userId)->get();
        $withdrawalsResource = WithdrawalResource::collection( $withdrawals );

        return ApiResponse::successResponseWithData( $withdrawalsResource, 'Withdrawals retrieved', 200 );
    }

    public function store(CreateWithdrawalRequest $request)
    {
        $userId = auth()->user()->id;
        $data = $request->validated();
        $data['user_id'] = $userId;
        $wallet = Wallet::where( 'user_id', $userId )->first();
        if( $wallet ){
            $balance = $wallet->balance;
            if( $balance >= $data['amount'] ){
                $withdrawal = Withdrawal::create( $data );
                $newBalance = $balance - $data['amount'];
                $wallet->update( [ 'balance' => $newBalance ] );

                $data['transaction_type'] = 'Withdrawal';

                $createTransaction = $this->transactionController::store( $data );

                $withdrawalResource = new WithdrawalResource( $withdrawal );

                return ApiResponse::successResponseWithData( $withdrawalResource, 'Withdrawal created', 200 );
            } else{
                return ApiResponse::errorResponse( 'Insufficient balance in wallet', 400);
            }

        } else{
            return ApiResponse::errorResponse();
        }


    }

}
