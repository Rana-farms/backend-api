<?php

namespace App\Repositories;
use App\Models\User;

class UserRepository implements RepositoryInterfaces\UserRepositoryInterface
{

    public function create(array $data)
    {
        $user = User::create( $data );
        return $user;
    }

    public function update(int $id, array $data)
    {
        // TODO: Implement update() method.
    }

}
