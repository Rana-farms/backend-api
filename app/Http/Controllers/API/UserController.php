<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserExistsRequest;
use App\Models\User;
use App\Traits\ApiResponse;

class UserController extends Controller
{
    public function checkIfUserExist( UserExistsRequest $request )
    {
        $data = $request->validated();
        $user = User::where( 'email', $data['email'] )->orWhere( 'phone', $data['phone'] )->first();
        if( $user ){
            return ApiResponse::errorResponse( 'User exists', 403 );
        } else{
            return ApiResponse::successResponse( 'User not found', 200 );
        }
    }
}
