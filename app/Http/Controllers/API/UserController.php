<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactUsRequest;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\UserExistsRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\NotifyAdminOfNewContactRequest;
use App\Notifications\NotifyInvestorOfVerifiedProfile;
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

    public function verifyUser(User $user)
    {
        $user->update(['verified' => true]);
        $userResource = new UserResource($user);
        Notification::route('mail', $user->email )->notify( (new NotifyInvestorOfVerifiedProfile( $user )) );
        return ApiResponse::successResponseWithData($userResource, 'User verified', 200);
    }

    public function show(User $user)
    {
        $userResource = new UserResource($user);
        $userResource->load( 'nextOfKin', 'bank', 'wallet', 'transactions', 'investments' );
        return ApiResponse::successResponseWithData($userResource, 'User retrived', 200);
    }

    public function contactUs(ContactUsRequest $request)
    {
        $data = $request->validated();
        Notification::route('mail', User::SUPERADMINEMAILS )->notify( (new NotifyAdminOfNewContactRequest( $data )) );

        return ApiResponse::successResponse('Message sent successfully!', 200);
    }
}
