<?php

namespace App\Http\Controllers\API;
use App\Repositories\RepositoryInterfaces\InvestorRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateInvestorProfile;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;

class InvestorController extends Controller
{
    public function __construct(InvestorRepositoryInterface $investorRepository)
    {
    $this->investorRepository = $investorRepository;
    }

    public function profile()
    {
        $userID = auth()->user()->id;
        $user = User::find( $userID );
        $userResource = new UserResource( $user );
        $userResource->load( 'nextOfKin', 'bank', 'wallet' );
        return ApiResponse::successResponseWithData( $userResource, 'Investor profile', 200 );
    }

    public function update( UpdateInvestorProfile $request )
    {
        $userId = auth()->user()->id;
        $user = User::find( $userId );
        if( $user ){
        $data = $request->validated();
        $updateProfile = $user->update( $data );
        $userResource = new UserResource( $user );
        $userResource->load( 'nextOfKin', 'bank', 'wallet' );
        return ApiResponse::successResponseWithData( $userResource, 'Profile updated successfully', 200 );
        } else{
            return ApiResponse::errorResponse( 'Profile not found', 404);
        }

    }
}
