<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserBankRequest;
use App\Http\Requests\UserBankRequest;
use App\Http\Resources\UserResource;
use App\Models\Bank;
use App\Models\User;
use App\Models\UserBank;
use Illuminate\Support\Facades\Http;
use App\Traits\ApiResponse;

class UserBankController extends Controller
{

    public function __construct(){
        $this->secret_key = config('app.secretKey');
        $this->public_key = config('app.publicKey');
    }

    public function store(AddUserBankRequest $request)
    {
        $userBankData =  $request->validated();
        $userId = auth()->user()->id;
        $user = User::find( $userId );
        $userBank = UserBank::where( 'user_id', $userId )->first();
        $getBank = Bank::find( $userBankData['bank_id'] );

        if( $getBank ){
            $userBankData['code'] = $getBank->paystack_code;
            if( $userBank ){
                $newUserbank = $userBank->update( $userBankData );
                $message = 'Bank updated successfully!';
            } else{
                $userBankData['user_id'] = $userId;
                $newUserbank = UserBank::create( $userBankData );
                $message = 'Bank added successfully!';
            }

            $userResource = new UserResource( $user );
            $userResource->load('bank');
            return ApiResponse::successResponseWithData( $userResource, $message, 200);

        } else{
            return ApiResponse::errorResponse( 'Incorrect bank details', 400 );
        }

    }

    public function delete( )
    {

        $userId = auth()->user()->id;
        $userBank = UserBank::where( 'user_id', $userId )->first();

        if( $userBank ){
        $userBank->delete();
        return ApiResponse::successResponse( 'Bank details deleted', 200 );
        } else{
            return ApiResponse::errorResponse( 'Bank details not found', 404 );
        }
    }

    public function resolveAccount(UserBankRequest $request)
    {
        $requestData =  $request->validated();
        $accountNumber = $requestData['account_no'];
        $bankId = $requestData['bank_id'];
        $bankInfo = Bank::find( $bankId );

        if ($bankInfo) {
            $response = Http::acceptJson()
                            ->withHeaders([
                                'Authorization' => "Bearer $this->secret_key",
                            ])
                            ->get('https://api.paystack.co/bank/resolve?account_number=' . $accountNumber . '&bank_code=' . $bankInfo->paystack_code);

            $result = json_decode($response->body(), true);

            if ($result['status'] == true) {
                $accountName = $result['data']['account_name'];
                $message = $result['message'];
                $data = [
                    'account_name' => $accountName,
                ];
                return ApiResponse::successResponseWithData( $data, $message, 200);

            } else {
                return ApiResponse::errorResponse($result['message'], 403);
            }
        } else{
            return ApiResponse::errorResponse('Bank details not found', 404);
    }
    }
}
