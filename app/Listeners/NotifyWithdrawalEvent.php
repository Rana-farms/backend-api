<?php

namespace App\Listeners;

use App\Events\WithdrawalEvent;
use App\Models\Withdrawal;
use App\Models\User;
use App\Notifications\NotifyAdminOfCompletedWithdrawal;
use App\Notifications\NotifyAdminOfProcessedWithdrawal;
use App\Notifications\NotifyInvestorOfCompletedWithdrawal;
use App\Notifications\NotifyInvestorOfProcessedWithdrawal;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyWithdrawalEvent
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(WithdrawalEvent $event)
    {
        $withdrawal = $event->withdrawal;
        $investor = $event->investor;
        $action = $event->action;

        if( $action == Withdrawal::PROCESSING ){
            Notification::route('mail', $investor['email'] )->notify( (new NotifyInvestorOfProcessedWithdrawal( $withdrawal, $investor )) );
            Notification::route('mail', User::SUPERADMINEMAILS )->notify( (new NotifyAdminOfProcessedWithdrawal( $withdrawal, $investor )) );
        }

        if( $action == Withdrawal::COMPLETED ){
            Notification::route('mail', $investor['email'] )->notify( (new NotifyInvestorOfCompletedWithdrawal( $withdrawal, $investor )) );
            Notification::route('mail', User::SUPERADMINEMAILS )->notify( (new NotifyAdminOfCompletedWithdrawal( $withdrawal, $investor )) );
        }
    }
}
