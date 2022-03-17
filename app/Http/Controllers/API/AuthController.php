<?php

namespace App\Http\Controllers\API;
use App\Repositories\RepositoryInterfaces\UserRepositoryInterface;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use NextApps\VerificationCode\VerificationCode;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Models\NextOfKin;
use App\Models\Bank;
use App\Models\UserBank;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Wallet;
use App\Traits\ApiResponse;

class AuthController extends Controller
{

    public function __construct(UserRepositoryInterface $userRepository

    ){
        $this->userRepository = $userRepository;
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
        $userData['role_id'] = 9;
        $newUser = $this->userRepository->create( $userData );
        $userData['user_id'] = $newUser->id;

        $createWallet = Wallet::create(['user_id' => $newUser->id]);
        $createNextOfKin = $this::createNextOfKin( $userData );
        $createUserBank = $this::createUserBank( $userData );

        VerificationCode::send( $newUser->email );

        $accessToken = $newUser->createToken('Auth Token')->plainTextToken;
        $user = new UserResource( $newUser );

        return ApiResponse::successResponseWithToken( $user, 'Registration successful', 200, $accessToken );
    }

    public static function createNextOfKin( $data )
    {
        $nextOfKinData = [
            'user_id' => $data['user_id'],
            'fullname' => $data['next_of_kin_fullname'],
            'address' => $data['next_of_kin_address'],
            'phone' => $data['next_of_kin_phone'],
            'relationship' => $data['relationship'],
        ];

        NextOfKin::create( $nextOfKinData );
    }

    public static function createUserBank( $data )
    {
        $getBank = Bank::find( $data['bank_id'] );

            $userBankData = [
                'user_id' => $data['user_id'],
                'bank_id' => $data['bank_id'],
                'account_name' => $data['account_name'],
                'code' => $getBank->paystack_code,
                'account_no' => $data['account_no'],
            ];

            UserBank::create( $userBankData );
    }
}
