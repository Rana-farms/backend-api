<?php

namespace App\Http\Controllers\API;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ResetPasswordNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Exception;
use App\Traits\ApiResponse;

class PasswordController extends Controller
{
    public function sendForgotPasswordLink(ForgotPasswordRequest $request)
    {
        $token = Str::random(24);
        $data = $request->validated();
        $email = $data['email'];
        $baseUrl = isset( $data['url'] ) ? $data['url'] : 'https://rana.com.ng';

        if (User::where('email', $email)->doesntExist()) {

            return ApiResponse::errorResponse( 'The supplied email does not exist!', 404 );
        }

        try {
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token,
            ]);
            $user = User::firstWhere('email', $email);

            $url = $baseUrl . '/reset-password/' . '?token=' . $token;
            Notification::route('mail', $email)->notify( ( new ResetPasswordNotification( $url ) ) );

            return ApiResponse::successResponse('Please check your email address for reset password link', 200);
        } catch (Exception $exception) {

            return ApiResponse::errorResponse($exception->getMessage(), 400);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $token = $request->input('token');
        $password = $request->input('password');
        $isTokenValid = DB::table('password_resets')->where( 'token', $token )->first();
        if ( !$isTokenValid ) {

            return ApiResponse::errorResponse('Token supplied is invalid!', 400);
        }

        $userEmail = $isTokenValid->email;
        $user = User::where('email', $userEmail)->first();
        if ( !$user ) {

            return ApiResponse::errorResponse('Invalid user', 404);
        }

        $user->password = Hash::make($password);
        $user->save();

        return ApiResponse::successResponse('Password update successful', 200);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $passwordData = $request->validated();

        $user = User::findOrFail(auth()->user()->id);
        if ( password_verify($passwordData['old_password'], $user->password ) ) {
            $user->password = Hash::make($passwordData['password']);
            if ($user->save()) {
                return ApiResponse::successResponse('Password change successful', 200);
            }
        } else {

            return ApiResponse::errorResponse('Incorrect password', 400);
        }
    }
}
