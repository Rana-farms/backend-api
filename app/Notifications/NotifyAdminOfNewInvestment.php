<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyAdminOfNewInvestment extends Notification
{
    use Queueable;
    public $investor;
    public $investment;
    public $userInvestment;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($investor, $investment, $userInvestment)
    {
        $this->investor = $investor;
        $this->investment = $investment;
        $this->userInvestment = $userInvestment;
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
        ->subject( 'New Investment' )
        ->greeting('Hello RANA Admin!')
        ->line($this->investor->fullname . ' has bought ' . $this->userInvestment->units . ' units of the ' . $this->investment->name . ' investment for ₦' . $this->userInvestment->amount . '.')
        ->line('Login to the RANA Super Admin dashboard to view the investment.');
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
