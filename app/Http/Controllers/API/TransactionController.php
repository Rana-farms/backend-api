<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Traits\ApiResponse;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::paginate();
        $transactions->load('user');
        $transactionsResource = TransactionResource::collection($transactions)->response()->getData(true);
        return ApiResponse::successResponseWithData($transactionsResource, 'Transactions retrieved', 200);
    }

    public static function store( $data )
    {
        Transaction::create( $data );
    }

    public static function update($typeId)
    {
        $transaction = Transaction::whereTypeId($typeId)->first();
        if( $transaction ){
            $transaction->update(['status' => 1]);
        }
    }
}
