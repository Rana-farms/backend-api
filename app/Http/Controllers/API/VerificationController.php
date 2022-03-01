<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyCodeRequest;
use NextApps\VerificationCode\VerificationCode;
use App\Models\User;
use App\Traits\ApiResponse;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    */


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only('resend');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verifyCode(VerifyCodeRequest $request)
    {
        $data = $request->validated();
        $verifyUser = VerificationCode::verify( $data['code'], $data['email'] );

        if( $verifyUser == false){
            return ApiResponse::errorResponse('Incorrect code supplied!', 404);
        } else{
            $user = User::where('email', $data['email'])->first();
            $user->email_verified_at = now();
            $user->save();
            return ApiResponse::successResponse('Verification successful', 200);
        }
    }

    public function resendVerifyCode(Request $request)
    {
        $data =  $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $user = User::where( 'email', $data['email'] )->first();
        if( $user ){
            if ($user->hasVerifiedEmail()) {
                return ApiResponse::successResponse('Email already verified', 200);
            }

            VerificationCode::send($request->email);

            if ($request->wantsJson()) {
                return ApiResponse::successResponse('Verification code sent', 200);
            }

        } else {
                return ApiResponse::errorResponse('The supplied email does not exist!', 404);
        }


    }

}
