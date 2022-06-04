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
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\ROIController;
use App\Http\Controllers\API\TransactionController;

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
            Route::get('dashboard-metrics', [InvestorController::class, 'getDashboardAnalytics']);
            Route::get('dashboard-graph', [InvestorController::class, 'getDashboardGraph']);

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

        // All Admins Route
        Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function () {
            Route::post('order', [OrderController::class, 'store'])->middleware('superadmin');
            Route::post('order/{code}', [OrderController::class, 'update'])->middleware('superadmin');
            Route::delete('order/{code}', [OrderController::class, 'destroy'])->middleware('superadmin');

            Route::post('document', [DocumentController::class, 'store']);
            Route::post('document/{code}', [DocumentController::class, 'update']);
            Route::delete('document/{code}', [DocumentController::class, 'destroy'])->middleware('superadmin');

            Route::post('create-roi', [ROIController::class, 'store']);

            Route::get('investors', [InvestorController::class, 'index']);
            Route::post('verify-user/{user}', [UserController::class, 'verifyUser']);
            Route::post('invite-admin', [AdminController::class, 'store'])->middleware('superadmin');
            Route::post('remove-admin', [AdminController::class, 'delete'])->middleware('superadmin');
            Route::post('manage-role', [AdminController::class, 'updateRole']);
            Route::get('withdrawals', [WithdrawalController::class, 'allWithdrawals']);
            Route::get('withdrawal/{withdrawal}', [WithdrawalController::class, 'show']);
            Route::get('admins', [AdminController::class, 'index']);
            Route::get('users/{user}', [UserController::class, 'show']);
            Route::get('dashboard-metrics', [AdminController::class, 'getDashboardAnalytics']);
            Route::get('dashboard-graph', [AdminController::class, 'getDashboardGraph']);
            Route::get('transactions', [TransactionController::class, 'index']);
            Route::post('approve-withdrawal/{withdrawal}', [WithdrawalController::class, 'confirm'])->middleware('superadmin');
            Route::post('approve-withdrawal-manual/{withdrawal}', [WithdrawalController::class, 'manualConfirm'])->middleware('superadmin');
        });

        //Documents
        Route::get('documents', [DocumentController::class, 'index']);
        Route::get('document/{code}', [DocumentController::class, 'show']);

        //Orders
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('order/{code}', [OrderController::class, 'show']);

        //Manage Profile
        Route::put('profile', [ProfileController::class, 'update']);
        Route::get('profile', [ProfileController::class, 'index']);

        //Change password
        Route::post('change-password', [PasswordController::class, 'changePassword']);
        Route::post('verify-payment/{reference}', [UserInvestmentController::class, 'verifyPayment']);
        Route::post('logout',[AuthController::class, 'logout']);
    });
});
