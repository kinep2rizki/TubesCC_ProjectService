<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class NewActivityLogged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $communityId;
    public $log;

    /**
     * Create a new event instance.
     */
    public function __construct($communityId, $log)
    {
        $this->communityId = $communityId;
        $this->log = $log;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        // Public channel for simplicity, since users on the page can see it anyway
        // or a presence/private channel.
        return [
            new Channel('community.' . $this->communityId . '.activities'),
        ];
    }
}
