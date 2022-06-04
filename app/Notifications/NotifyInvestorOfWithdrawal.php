<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyInvestorOfWithdrawal extends Notification
{
    use Queueable;
    public $investor;
    public $withdrawal;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($investor, $withdrawal)
    {
        $this->investor = $investor;
        $this->withdrawal = $withdrawal;

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
        ->subject( 'New Withdrawal Request' )
        ->greeting('Hello '. $this->investor->fullname .'!')
        ->line('Your withdrawal request of ₦' . $this->withdrawal->amount . ' has been received.')
        ->line('The request will be processed within 1 to 3 business working days.')
        ->line('You will be notified by mail about the status of the request.')
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
