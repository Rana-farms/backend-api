<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyInvestorOfNewInvestment extends Notification
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
        ->greeting('Hello '. $this->investor->fullname .'!')
        ->line('You have bought ' . $this->userInvestment->units . ' units of the ' . $this->investment->name . ' investment for ₦' . $this->userInvestment->amount . '.')
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
