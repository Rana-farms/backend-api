<?php

namespace App\Repositories\RepositoryInterfaces;

interface AdminRepositoryInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
}
