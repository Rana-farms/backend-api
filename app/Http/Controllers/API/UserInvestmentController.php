<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserInvestmentRequest;
use App\Http\Resources\UserInvestmentResource;
use App\Models\Investment;
use App\Models\UserInvestment;
use App\Traits\ApiResponse;
use Carbon\Carbon;

class UserInvestmentController extends Controller
{

    public function index()
    {
        $userId = auth()->user()->id;
        $userInvestments = UserInvestment::where('user_id', $userId)->get();
        $userInvestmentsResource = UserInvestmentResource::collection($userInvestments);
        return ApiResponse::successResponseWithData($userInvestmentsResource, 'Investments retrieved', 203);
    }


    public function store(CreateUserInvestmentRequest $request)
    {
        $data = $request->validated();
        $userId = auth()->user()->id;
        $data['user_id'] = $userId;
        $getInvestment = Investment::find($data['investment_id']);
        $minimumUnits = $getInvestment->minimum_unit;
        $pricePerUnit = $getInvestment->unit_price;

        if ($data['units'] >= $minimumUnits) {
            $amount = $pricePerUnit * $data['units'];
            $data['amount'] = $amount;

            $userInvestment = UserInvestment::create($data);
            $userInvestmentResource = new UserInvestmentResource($userInvestment);
            return ApiResponse::successResponseWithData($userInvestmentResource, 'Investment created', 203);
        } else {

            $data = [
                'minimum_unit' => $minimumUnits,
            ];

            return ApiResponse::errorResponseWithData($data, 'Unit is below the required minimum unit for the selected investment', 400);
        }
    }


    public function show(UserInvestment $investment)
    {
        $userId = auth()->user()->id;
        $investmentUserId = $investment->user_id;
        if ($investmentUserId === $userId) {
            $investmentResource = new UserInvestmentResource($investment);
            return ApiResponse::successResponseWithData($investmentResource, 'Investment retrieved', 200);
        } else {
            return ApiResponse::errorResponse('You are unauthorized to view this resource', 403);
        }
    }

    public static function update($data)
    {
        $userInvestment = UserInvestment::find($data['investment_id']);

        $data = [
            'payment_id' => $data['payment_id'],
            'status' => 1,
            'is_paid' => 1,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonths( $data['period'] ),
        ];

        $userInvestment->update($data);
    }


    public function destroy(UserInvestment $investment)
    {
        $userId = auth()->user()->id;
        $investmentUserId = $investment->user_id;
        if ($investmentUserId === $userId) {
            if (!empty($investment->is_paid)) {
                $investment->delete();
                return ApiResponse::successResponse('Investment deleted', 200);
            } else {
                return ApiResponse::errorResponse('You can not delete this investment', 403);
            }
        } else {
            return ApiResponse::errorResponse('You are unauthorized to view this resource', 403);
        }
    }
}
