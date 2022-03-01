<?php

namespace App\Repositories;
use App\Models\User;

class EmployeeRepository implements RepositoryInterfaces\EmployeeRepositoryInterface
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
