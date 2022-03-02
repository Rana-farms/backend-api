<?php

namespace App\Repositories;
use App\Models\Employee;

class EmployeeRepository implements RepositoryInterfaces\EmployeeRepositoryInterface
{

    public function create(int $userId)
    {
        $employee = Employee::create([ 'user_id' =>  $userId ]);
        return $employee;
    }

    public function update(int $id, array $data)
    {

    }

}
