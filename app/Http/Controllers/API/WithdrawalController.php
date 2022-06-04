<?php

namespace App\Http\Controllers\API;

use App\Events\WithdrawalEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateWithdrawalRequest;
use App\Http\Resources\WithdrawalResource;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\API\TransactionController;
use App\Traits\ApiResponse;

class WithdrawalController extends Controller
{
    public function __construct(){
        $this->transactionController = new TransactionController();
        $this->paymentController = new PaymentController();
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
        $wallet = Wallet::firstWhere( 'user_id', $userId );
        $balance = $wallet->balance;
        $user = User::find($userId);

        if ( ! password_verify($data['password'], $user->password ) ) {
            return ApiResponse::errorResponse('Incorrect password', 400);
        }

        if( $balance < $data['amount'] ){
            return ApiResponse::errorResponse( 'Insufficient balance in wallet', 400);
        }

        $withdrawal = Withdrawal::create( $data );
        $newBalance = $balance - $data['amount'];
        $wallet->update( [ 'balance' => $newBalance ] );

        $data['transaction_type'] = 'Withdrawal';
        $data['type_id'] = $withdrawal->id;
        $createTransaction = $this->transactionController::store( $data );
        $withdrawalResource = new WithdrawalResource( $withdrawal );

        return ApiResponse::successResponseWithData( $withdrawalResource, 'Withdrawal created', 200 );
    }

    public function allWithdrawals()
    {
        $withdrawals = Withdrawal::get();
        $withdrawalsResource = WithdrawalResource::collection( $withdrawals );
        return ApiResponse::successResponseWithData( $withdrawalsResource, 'Withdrawals retrieved', 200 );
    }

    public function confirm(Withdrawal $withdrawal)
    {
        if( $withdrawal->status != 'Pending'){
            return ApiResponse::errorResponse('Withdrawal already processed', 400);
        }

        $investorId = $withdrawal->user_id;
        $investor = User::find($investorId);
        $withdrawal->update(['status' => 'Processing']);
        $withdrawalResource = new WithdrawalResource($withdrawal);
        // $processPayment = $this->paymentController::store( $investor );
        WithdrawalEvent::dispatch($withdrawal, $investor, Withdrawal::PROCESSING);
        return ApiResponse::successResponseWithData($withdrawalResource, 'Withdrawal approved', 200);
    }

    public function manualConfirm(Withdrawal $withdrawal)
    {
        if( $withdrawal->status != 'Pending'){
            return ApiResponse::errorResponse('Withdrawal already processed', 400);
        }

        $investorId = $withdrawal->user_id;
        $investor = User::find($investorId);
        $withdrawal->update(['status' => 'Completed']);
        $withdrawalResource = new WithdrawalResource($withdrawal);
        $updateTransaction = $this->transactionController::update($withdrawal->id);
        WithdrawalEvent::dispatch($withdrawal, $investor, Withdrawal::COMPLETED);
        return ApiResponse::successResponseWithData($withdrawalResource, 'Withdrawal completed', 200);
    }

    public function show(Withdrawal $withdrawal)
    {
        $withdrawalResource = new WithdrawalResource($withdrawal);
        return ApiResponse::successResponseWithData($withdrawalResource, 'Withdrawal retrieved', 200);
    }


}
