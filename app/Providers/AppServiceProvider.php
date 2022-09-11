<?php

namespace App\Providers;

use App\Models\Investment;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NotifyInvestorOfWithdrawal;
use App\Notifications\NotifyAdminOfWithdrawal;
use App\Models\User;
use App\Models\UserInvestment;
use App\Models\Withdrawal;
use App\Notifications\NotifyAdminOfNewInvestment;
use App\Notifications\NotifyAdminOfNewInvestor;
use App\Notifications\NotifyInvestorOfNewInvestment;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Withdrawal::created( function( $withdrawal ) {
            $investor = User::find($withdrawal->user_id);
            Notification::route('mail', $investor->email )->notify( (new NotifyInvestorOfWithdrawal( $investor, $withdrawal )) );
            Notification::route('mail', User::SUPERADMINEMAILS )->notify( (new NotifyAdminOfWithdrawal( $investor, $withdrawal )) );
        });

        User::created( function( $user ) {
            $role = $user->role_id;
            if($role == 9){
                Notification::route('mail', User::SUPERADMINEMAILS )->notify( (new NotifyAdminOfNewInvestor( $user )) );
            }
        });

        UserInvestment::created( function( $userInvestment ) {
            $investor = User::find($userInvestment->user_id);
            $investment = Investment::find($userInvestment->investment_id);
            Notification::route('mail', User::SUPERADMINEMAILS )->notify( (new NotifyAdminOfNewInvestment( $investor, $investment, $userInvestment )) );
            Notification::route('mail', $investor->email )->notify( (new NotifyInvestorOfNewInvestment( $investor, $investment, $userInvestment )) );
        });
    }
}
