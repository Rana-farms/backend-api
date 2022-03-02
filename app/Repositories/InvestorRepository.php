<?php

namespace App\Repositories;
use App\Models\Investor;

class InvestorRepository implements RepositoryInterfaces\InvestorRepositoryInterface
{

    public function create(int $userId)
    {
        $investor = Investor::create([ 'user_id' =>  $userId ]);
        return $investor;
    }

    public function update(int $id, array $data)
    {
        $investor = Investor::where('user_id', $id)->first();
        $investor->update( $data );
        return $investor;
    }

}
