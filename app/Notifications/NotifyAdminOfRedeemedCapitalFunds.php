<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyAdminOfRedeemedCapitalFunds extends Notification
{
    use Queueable;
    public $investment;
    public $investor;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($investor, $investment)
    {
        $this->investment = $investment;
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
        ->subject( 'New Capital Funds Redeemed' )
        ->greeting('Hello RANA Admin!')
        ->line($this->investor->fullname . ' has redeemed their capital funds of ₦' . $this->investment->amount );
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
