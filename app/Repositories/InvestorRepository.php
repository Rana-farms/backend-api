<?php

namespace App\Repositories;
use App\Models\User;

class InvestorRepository implements RepositoryInterfaces\InvestorRepositoryInterface
{

    public function create(array $data)
    {
        $user = User::create( $data );
        return $user;
    }

    public function update(int $id, array $data)
    {
   
    }

}
