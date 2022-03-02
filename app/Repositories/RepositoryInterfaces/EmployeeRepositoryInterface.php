<?php

namespace App\Repositories\RepositoryInterfaces;

interface EmployeeRepositoryInterface
{
    public function create(int $userId);
    public function update(int $id, array $data);

}
