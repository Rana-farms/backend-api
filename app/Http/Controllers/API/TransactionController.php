<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public static function store( $data )
    {
        Transaction::create( $data );
    }
}
