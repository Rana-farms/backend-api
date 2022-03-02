<?php

namespace App\Repositories\RepositoryInterfaces;

interface InvestorRepositoryInterface
{
    public function create(int $userId);
    public function update(int $id, array $data);
}
