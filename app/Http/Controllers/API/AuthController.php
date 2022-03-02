<?php

namespace App\Http\Controllers\API;
use App\Repositories\RepositoryInterfaces\UserRepositoryInterface;
use App\Repositories\RepositoryInterfaces\InvestorRepositoryInterface;
use App\Repositories\RepositoryInterfaces\EmployeeRepositoryInterface;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use NextApps\VerificationCode\VerificationCode;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponse;

class AuthController extends Controller
{

    public function __construct(UserRepositoryInterface $userRepository,
                                InvestorRepositoryInterface $investorRepository,
                                EmployeeRepositoryInterface $employeeRepository
    ){
        $this->userRepository = $userRepository;
        $this->investorRepository = $investorRepository;
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * Login User
     */

    public function login( LoginRequest $request )
    {

        $userData = $request->validated();

        if ( Auth::attempt( $userData ) ) {
            $accessToken = Auth::user()->createToken('Auth Token')->plainTextToken;
            $user = new UserResource( auth()->user() );

            return ApiResponse::successResponseWithToken( $user, 'Login successful', 200, $accessToken );
        }

        return ApiResponse::errorResponse( 'Invalid Login credentials', 400 );
    }

     /**
     * Register user
     */

    public function register( CreateUserRequest $request )
    {
        $userData = $request->validated();
        $userData['password'] = Hash::make( $userData['password'] );
        $userData['role_id'] = 9;
        $newUser = $this->userRepository->create( $userData );

        $createProfile = $this->createProfile( $newUser );

        VerificationCode::send( $newUser->email );

        $accessToken = $newUser->createToken('Auth Token')->plainTextToken;
        $user = new UserResource( $newUser );

        return ApiResponse::successResponseWithToken( $user, 'Registration successful', 200, $accessToken );

    }

    public function createProfile( $user )
    {
        $roleId = $user->role_id;

        if( $roleId == 9 ){
            $createInvestorProfile = $this->investorRepository->create( $user->id );
        }

        if( $roleId == 18 ){
            $createEmployeeProfile = $this->employeeRepository->create( $user->id );
        }
    }
}
