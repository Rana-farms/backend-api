<?php
namespace App\Repositories;
use App\Models\Wallet;

class WalletRepository implements RepositoryInterfaces\WalletRepositoryInterface
{

    public function createOrUpdate( int $userID, array $data )
    {
        $wallet = Wallet::where('user_id', $userID)->first();

            if( $wallet ){
                $wallet->update( $data );
            } else{
                $wallet = Wallet::create( $data );
            }

    }

}
