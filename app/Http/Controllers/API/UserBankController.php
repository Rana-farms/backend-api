<?php

namespace App\Http\Controllers\API;
use App\Http\Resources\UserBankResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserBankRequest;
use App\Http\Requests\UserBankRequest;
use App\Models\Bank;
use App\Models\UserBank;
use Illuminate\Support\Facades\Http;
use App\Traits\ApiResponse;

class UserBankController extends Controller
{

    public function __construct(){
        $this->secret_key = config('app.secretKey');
        $this->public_key = config('app.publicKey');
    }

    public function index()
    {
        $userBanks = UserBank::where('user_id', auth()->user()->id)->get();
        $userBanksResource = UserBankResource::collection( $userBanks );
        return ApiResponse::successResponseWithData( $userBanksResource, 'Banks for user retrieved', 200);
    }

    public function store(AddUserBankRequest $request)
    {
        $userBank =  $request->validated();
        $userBank['user_id'] = auth()->user()->id;
        $checkIfExist = UserBank::where(['account_no' => $userBank['account_no'], 'bank_id' => $userBank['bank_id'] ])->first();
        $getBank = Bank::find($userBank['bank_id']);
        if ( !$checkIfExist ) {
            if( $getBank ){
                $userBank['code'] = $getBank->paystack_code;
                $newUserbank = UserBank::create( $userBank );
                $bankResource = new UserBankResource( $newUserbank );
                return ApiResponse::successResponseWithData( $bankResource, 'Bank added successfully', 203);
            } else{
                return ApiResponse::errorResponse('Incorrect bank details', 400);
            }
        } else{
            return ApiResponse::errorResponse('Bank details already exist, please use another!', 400);
        }
    }

    public function delete( $id )
    {
        $getUserBank = UserBank::find( $id );
        $userId = auth()->user()->id;

        if( $getUserBank ){
            if( $getUserBank->user_id == $userId){
                $getUserBank->delete();
                return ApiResponse::successResponse('Bank details deleted', 200);
            } else{
                return ApiResponse::errorResponse('You are unauthorized to delete this bank details', 403);
            }
        } else{
            return ApiResponse::errorResponse('Bank details not found', 404);
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
