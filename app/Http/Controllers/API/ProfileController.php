<?php

namespace App\Http\Controllers\API;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class ProfileController extends Controller
{
    public function index()
    {
        $userID = auth()->user()->id;
        $user = User::find( $userID );
        $userResource = new UserResource( $user );
        $userResource->load( 'nextOfKin', 'bank', 'wallet', 'transactions', 'investments');
        return ApiResponse::successResponseWithData( $userResource, 'User profile', 200 );
    }

    public function update( UpdateProfileRequest $request )
    {
        $userId = auth()->user()->id;
        $user = User::find( $userId );
        if( $user ){
        $data = $request->validated();

        if ($request->hasFile('identity_document')) {
            $data['identity_document'] = $request->file('identity_document')->store('identity');
          }

        $updateProfile = $user->update( $data );
        $userResource = new UserResource( $user );
        $userResource->load(  'nextOfKin', 'bank', 'wallet', 'transactions', 'investments' );
        return ApiResponse::successResponseWithData( $userResource, 'Profile updated successfully', 200 );
        } else{
            return ApiResponse::errorResponse( 'Profile not found', 404);
        }

    }
}
