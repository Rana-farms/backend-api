<?php

namespace App\Http\Controllers\API;
use App\Repositories\RepositoryInterfaces\NextOfKinRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\NextOfKinRequest;
use App\Http\Resources\NextOfKinResource;
use App\Http\Resources\UserResource;
use App\Models\NextOfKin;
use App\Models\User;
use App\Traits\ApiResponse;

class NextOfKinController extends Controller
{
    public function __construct(NextOfKinRepositoryInterface $nextOfKinRepository)
    {
    $this->nextOfKinRepository = $nextOfKinRepository;
    }

    public function index()
    {
        $nextOfKin = NextOfKin::where('user_id', auth()->user()->id)->get();
        $nextOfKinResource = NextOfKinResource::collection( $nextOfKin );
        return ApiResponse::successResponseWithData( $nextOfKinResource, 'Next of Kin for user retrieved', 200);
    }

    public function store(NextOfKinRequest $request)
    {
        $userID = auth()->user()->id;
        $user = User::find( $userID );
        $nextOfKinData =  $request->validated();
        $nextOfKinData['user_id'] = $userID;
        $nextOfKin = $this->nextOfKinRepository->createOrUpdate( $userID, $nextOfKinData );
        $userResource = new UserResource( $user );
        $userResource->load('nextOfKin');
        return ApiResponse::successResponseWithData( $userResource, 'Next of Kin updated', 200);
    }

    public function delete( )
    {

        $userId = auth()->user()->id;
        $nextOfKin = NextOfKin::where( 'user_id', $userId )->first();

        if( $nextOfKin ){
        $nextOfKin->delete();
        return ApiResponse::successResponse( 'Next of kin details deleted', 200 );
        } else{
            return ApiResponse::errorResponse( 'Next of kin details not found', 404 );
        }
    }
}
