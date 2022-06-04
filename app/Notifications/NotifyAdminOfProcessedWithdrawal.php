<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyAdminOfProcessedWithdrawal extends Notification
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
        ->subject( 'Withdrawal Processing' )
        ->greeting('Hello RANA Admin!')
        ->line('A withdrawal request of ₦' . $this->withdrawal->amount . ' from ' . $this->investor->fullname . ' is been processed.' )
        ->line('You will be notified by mail about the completion status of the request.');
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
