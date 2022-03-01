<?php

namespace App\Repositories;
use App\Models\User;

class AdminRepository implements RepositoryInterfaces\AdminRepositoryInterface
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
