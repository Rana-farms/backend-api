<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BankResource;
use App\Models\Bank;
use App\Traits\ApiResponse;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::all();
        $banksResource = BankResource::collection( $banks );
        return ApiResponse::successResponseWithData( $banksResource, 'Banks retrieved successfully', 200);
    }
}
