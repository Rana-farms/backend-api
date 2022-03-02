<?php

namespace App\Repositories;
use App\Models\NextOfKin;

class NextOfKinRepository implements RepositoryInterfaces\NextOfKinRepositoryInterface
{

    public function create(array $data)
    {
        $nextOfKin = NextOfKin::create( $data );
        return $nextOfKin;
    }

    public function update(int $id, array $data)
    {
        $nextOfKin = NextOfKin::find( $id );
        $nextOfKin->update( $data );
        return $nextOfKin;
    }

    public function getById( int $id )
    {
        return NextOfKin::where('id', $id )->first();
    }

    public function getForUser()
    {
        return NextOfKin::where('user_id', auth()->user()->id )->get();
    }

    public function delete(int $id)
    {
        $nextOfKin = NextOfKin::findOrFail( $id );
        $nextOfKin->delete();
    }

}
