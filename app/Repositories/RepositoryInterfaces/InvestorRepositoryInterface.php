<?php

namespace App\Repositories\RepositoryInterfaces;

interface InvestorRepositoryInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
}
