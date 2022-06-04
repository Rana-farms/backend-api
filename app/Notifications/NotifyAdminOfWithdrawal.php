<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyAdminOfWithdrawal extends Notification
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

    //

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
        ->greeting('Hello RANA Admin!')
        ->line($this->investor->fullname . ' has made a new withdrawal request of ₦' . $this->withdrawal->amount )
        ->line('Login to the RANA Super Admin dashboard to approve this withdrawal request.');
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
