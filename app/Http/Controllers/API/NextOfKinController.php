<?php

namespace App\Http\Controllers\API;
use App\Repositories\RepositoryInterfaces\NextOfKinRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\NextOfKinRequest;
use App\Http\Resources\NextOfKinResource;
use App\Models\NextOfKin;
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
        $nextOfKinData =  $request->validated();
        $nextOfKinData['user_id'] = auth()->user()->id;
        $nextOfKin = $this->nextOfKinRepository->create( $nextOfKinData );
        $nextOfKinResource = new NextOfKinResource( $nextOfKin );
        return ApiResponse::successResponseWithData( $nextOfKinResource, 'Next of Kin added', 200);
    }

    public function update( NextOfKinRequest $request, $id)
    {
        $nextOfKin = NextOfKin::find( $id );
        $userId = auth()->user()->id;
        if( $nextOfKin ){
            if( $nextOfKin->user_id == $userId ){
                $nextOfKinData = $request->validated();
                $nextOfKinToUpdate = $this->nextOfKinRepository->update( $id, $nextOfKinData );
                $nextOfKinResource = new NextOfKinResource( $nextOfKinToUpdate );
                return ApiResponse::successResponseWithData( $nextOfKinResource, 'Next of kin updated successfully', 200 );
            } else{
                return ApiResponse::errorResponse('You are unauthorized to update this next of kin details', 403);
            }

        } else{
            return ApiResponse::errorResponse('Next of kin details not found', 404);
        }

    }

    public function delete( $id )
    {
        $nextOfKin = NextOfKin::find( $id );
        $userId = auth()->user()->id;

        if( $nextOfKin ){
            if( $nextOfKin->user_id == $userId){
                $nextOfKin->delete();
                return ApiResponse::successResponse('Next of kin details deleted', 200);
            } else{
                return ApiResponse::errorResponse('You are unauthorized to delete this next of kin details', 403);
            }
        } else{
            return ApiResponse::errorResponse('Next of kin details not found', 404);
        }
    }
}
