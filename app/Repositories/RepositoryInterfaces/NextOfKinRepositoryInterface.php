<?php

namespace App\Repositories\RepositoryInterfaces;

interface NextOfKinRepositoryInterface
{
    public function createOrUpdate(int $userID, array $data);
    public function getById( int $id );
    public function getForUser( );
    public function delete( int $id );
}
