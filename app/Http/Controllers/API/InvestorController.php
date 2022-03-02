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

    public function update( UpdateInvestorProfile $request)
    {
        $userId = auth()->user()->id;
        $getInvestor = User::find( $userId );
        if( $getInvestor ){
        $profileData = $request->validated();
        $updateInvestor = $this->investorRepository->update( $userId, $profileData );
        $investorResource = new UserResource( $getInvestor );
        return ApiResponse::successResponseWithData( $investorResource, 'Investor updated successfully', 200 );
        } else{
            return ApiResponse::errorResponse( 'Investor not found', 404);
        }

    }
}
