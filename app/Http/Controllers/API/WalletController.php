<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{

    public function getInvestorBalance($investorId)
    {
        $wallet = Wallet::whereUserId($investorId)->first();
        if( ! $wallet ){
            $wallet = Wallet::create(['user_id' => $investorId]);
        }
        $balance = $wallet->balance;
        return $balance;
    }

    public function addRoiToInvestorWallet($userId, $amount)
    {
        $wallet = Wallet::whereUserId($userId)->first();
        if( ! $wallet ){
            $wallet = Wallet::create(['user_id' => $userId]);
        }
        $wallet->balance = $wallet->balance + $amount;
        $wallet->save();
    }
}
