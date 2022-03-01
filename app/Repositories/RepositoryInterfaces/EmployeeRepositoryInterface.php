<?php

namespace App\Repositories\RepositoryInterfaces;

interface EmployeeRepositoryInterface
{
    public function create(array $data);
    public function update(int $id, array $data);

}
