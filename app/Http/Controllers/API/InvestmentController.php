<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Investor;
use App\Http\Requests\CheckInvestmentMinimumUnitsRequest;
use App\Http\Resources\InvestmentResource;
use App\Models\Investment;
use App\Traits\ApiResponse;

class InvestmentController extends Controller
{

    public function index()
    {
        $investments = Investment::all();
        $investmentsResource = InvestmentResource::collection( $investments );

        return ApiResponse::successResponseWithData( $investmentsResource, 'Investments retrieved', 200);
    }


    public function store(Request $request)
    {
        //
    }


    public function show( Investment $investment )
    {
        $investmentResource = new InvestmentResource( $investment );
        return ApiResponse::successResponseWithData( $investmentResource, 'Investment retrieved', 200);
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy( Investment $investment )
    {
        $investment->delete();
        return ApiResponse::successResponse('Investment deleted', 200);
    }

    public function checkMinimumUnit(CheckInvestmentMinimumUnitsRequest $request )
    {
        $data = $request->validated();
        $investment = Investment::find( $data['investment_id']);
        $minimumUnits = $investment->minimum_unit;

        if( $data['units'] < $minimumUnits ){

            $data = [
                'minimum_unit' => $minimumUnits,
            ];

            return ApiResponse::errorResponseWithData( $data, 'Unit is below the required minimum unit for the selected investment', 400);
        } else{
            return ApiResponse::successResponse( 'Unit satisfies the required minimum unit for the selected investment', 200);
        }
    }
}
