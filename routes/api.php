<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\InvestorController;
use App\Http\Controllers\API\UserBankController;
use App\Http\Controllers\API\BankController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\VerificationController;
use App\Http\Controllers\API\PasswordController;
use App\Http\Controllers\API\NextOfKinController;
use App\Http\Controllers\API\InvestmentController;
use App\Http\Controllers\API\WithdrawalController;
use App\Http\Controllers\API\UserInvestmentController;
use App\Http\Controllers\API\PaymentController;

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
    Route::get('banks', [BankController::class, 'index']);
    Route::post('user-exists', [UserController::class, 'checkIfUserExist']);

    //Authenticated Routes Group
    Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {

        // Investors Route
        Route::group(['prefix' => 'investor', 'middleware' => ['investor']], function () {
            Route::put('profile', [InvestorController::class, 'update']);
            Route::get('profile', [InvestorController::class, 'profile']);

            Route::post('update-bank', [UserBankController::class, 'store']);
            Route::delete('bank', [UserBankController::class, 'delete']);
            Route::post('resolve-account', [UserBankController::class, 'resolveAccount']);

            Route::post('next-of-kin', [NextOfKinController::class, 'store']);
            Route::delete('next-of-kin', [NextOfKinController::class, 'delete']);

            Route::get('withdrawals', [WithdrawalController::class, 'index']);
            Route::post('withdrawal', [WithdrawalController::class, 'store']);

            Route::get('investments', [UserInvestmentController::class, 'index']);
            Route::post('investment', [UserInvestmentController::class, 'store']);
            Route::get('investment/{investment}', [UserInvestmentController::class, 'show']);
            Route::delete('investment/{investment}', [UserInvestmentController::class, 'destroy']);

            Route::post('process-payment', [PaymentController::class, 'processInvestmentPayment']);

        });

        Route::get('investments', [InvestmentController::class, 'index']);
        Route::get('investment/{investment}', [InvestmentController::class, 'show']);
        Route::post('confirm-minimum-unit', [InvestmentController::class, 'checkMinimumUnit']);

        // Admins Route
        Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function () {
        });

        // Employees Route
        Route::group(['prefix' => 'employee', 'middleware' => ['employee']], function () {
        });

        //Change password
        Route::post('change-password', [PasswordController::class, 'changePassword']);
    });
});
