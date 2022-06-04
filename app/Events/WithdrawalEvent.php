<?php

namespace App\Events;

use App\Models\Withdrawal;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WithdrawalEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $withdrawal;
    public $investor;
    public $action;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Withdrawal $withdrawal, $investor, $action)
    {
        $this->withdrawal = $withdrawal;
        $this->investor = $investor;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
