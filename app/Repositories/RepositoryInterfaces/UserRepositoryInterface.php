<?php

namespace App\Repositories\RepositoryInterfaces;

interface UserRepositoryInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
}
