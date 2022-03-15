<?php

namespace App\Repositories\RepositoryInterfaces;

interface WalletRepositoryInterface
{
    public function createOrUpdate(int $userID, array $data);
}
