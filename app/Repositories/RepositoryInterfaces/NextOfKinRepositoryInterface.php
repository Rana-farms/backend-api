<?php

namespace App\Repositories\RepositoryInterfaces;

interface NextOfKinRepositoryInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
    public function getById( int $id );
    public function getForUser( );
    public function delete( int $id );
}
