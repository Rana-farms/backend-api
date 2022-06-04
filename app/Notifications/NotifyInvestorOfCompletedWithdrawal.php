<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyInvestorOfCompletedWithdrawal extends Notification
{
    use Queueable;
    public $withdrawal;
    public $investor;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($withdrawal, $investor)
    {
        $this->withdrawal = $withdrawal;
        $this->investor = $investor;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject( 'Withdrawal Completed' )
        ->greeting('Hello '. $this->investor->fullname .'!')
        ->line('Your withdrawal request of ₦' . $this->withdrawal->amount . ' has been completed, and payout is done.')
        ->line('Please contact the admin if you are yet to recieve the payment into your account.')
        ->line('Thanks for investing with RANA Farms.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
