<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyAdminOfCompletedWithdrawal extends Notification
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
        ->greeting('Hello RANA Admin!')
        ->line('A withdrawal request of ₦' . $this->withdrawal->amount . ' from ' . $this->investor->fullname . ' has been completed and successfully paid out.' );
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
