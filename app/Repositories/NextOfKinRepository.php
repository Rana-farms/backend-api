<?php
namespace App\Repositories;
use App\Models\NextOfKin;

class NextOfKinRepository implements RepositoryInterfaces\NextOfKinRepositoryInterface
{

    public function createOrUpdate( int $userID, array $data )
    {
        $nextOfKin = NextOfKin::where('user_id', $userID)->first();

            if( $nextOfKin ){
                $nextOfKin->update( $data );
            } else{
                $nextOfKin = NextOfKin::create( $data );
            }

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
