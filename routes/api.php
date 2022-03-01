<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\InvestorController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\VerificationController;
use App\Http\Controllers\API\PasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/



Route::group(['middleware' => ['json']], function () {

    //Unauthenticated Routes
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('forgot-password', [PasswordController::class, 'sendForgotPasswordLink'])->name('password.request');
    Route::post('reset-password', [PasswordController::class, 'resetPassword'])->name('password.reset');
    Route::post('verify-code', [VerificationController::class, 'verifyCode'])->name('verify-code');
    Route::post('resend-verify-code', [VerificationController::class, 'resendVerifyCode'])->name('resend-verify-code');

    //Authenticated Routes Group
    Route::group(['middleware' => ['auth:sanctum']], function () {

    // Investors Route
    Route::group(['prefix' => 'investor', 'middleware' => ['investor']], function () {

    });

    // Admins Route
    Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function () {

    });

    // Employees Route
    Route::group(['prefix' => 'employee', 'middleware' => ['employee']], function () {

    });


});

});


