<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LoguinAttemptEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id, $email, $ip, $user_agent, $status;

    /**
     * Create a new event instance.
     */
    public function __construct($id, $email, $ip, $user_agent, $status)
    {
        $this->id = $id;
        $this->email = $email;
        $this->ip = $ip;
        $this->user_agent = $user_agent;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
