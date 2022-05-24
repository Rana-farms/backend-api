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
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\WithdrawalController;
use App\Http\Controllers\API\UserInvestmentController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\DocumentController;
use App\Http\Controllers\API\ROIController;

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
    Route::post('resolve-account', [UserBankController::class, 'resolveAccount']);

    //Authenticated Routes Group
    Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {

        // Investors Route
        Route::group(['prefix' => 'investor', 'middleware' => ['investor']], function () {
            Route::put('profile', [InvestorController::class, 'update']);
            Route::get('profile', [InvestorController::class, 'profile']);
            Route::get('dashboard-metrics', [InvestorController::class, 'getDashboardAnalytics']);

            Route::post('update-bank', [UserBankController::class, 'store']);
            Route::delete('bank', [UserBankController::class, 'delete']);

            Route::post('next-of-kin', [NextOfKinController::class, 'store']);
            Route::delete('next-of-kin', [NextOfKinController::class, 'delete']);

            Route::get('withdrawals', [WithdrawalController::class, 'index']);
            Route::post('withdrawal', [WithdrawalController::class, 'store']);

            Route::get('investments', [UserInvestmentController::class, 'index']);
            Route::post('investment', [UserInvestmentController::class, 'store']);
            Route::get('investment/{investment}', [UserInvestmentController::class, 'show']);
            Route::delete('investment/{investment}', [UserInvestmentController::class, 'destroy']);
        });

        Route::get('investments', [InvestmentController::class, 'index']);
        Route::get('investment/{investment}', [InvestmentController::class, 'show']);
        Route::post('confirm-minimum-unit', [InvestmentController::class, 'checkMinimumUnit']);

        // Super Admins Route
        Route::group(['prefix' => 'admin', 'middleware' => ['superadmin']], function () {

        });

        // All Admins Route
        Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function () {
            Route::get('orders', [OrderController::class, 'index']);
            Route::post('order', [OrderController::class, 'store']);
            Route::post('order/{code}', [OrderController::class, 'update']);
            Route::get('order/{code}', [OrderController::class, 'show']);
            Route::delete('order/{code}', [OrderController::class, 'destroy']);

            Route::get('documents', [DocumentController::class, 'index']);
            Route::post('document', [DocumentController::class, 'store']);
            Route::post('document/{code}', [DocumentController::class, 'update']);
            Route::get('document/{code}', [DocumentController::class, 'show']);
            Route::delete('document/{code}', [DocumentController::class, 'destroy']);

            Route::post('create-roi', [ROIController::class, 'store']);
        });

        //Change password
        Route::post('change-password', [PasswordController::class, 'changePassword']);
        Route::post('verify-payment/{reference}', [UserInvestmentController::class, 'verifyPayment']);
    });
});
