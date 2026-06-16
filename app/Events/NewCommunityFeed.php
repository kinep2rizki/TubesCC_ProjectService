<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewCommunityFeed implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $communityId;
    public $feedData;

    public function __construct($communityId, $feedData)
    {
        $this->communityId = $communityId;
        $this->feedData = $feedData;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('community.' . $this->communityId . '.feed'),
        ];
    }
    
    public function broadcastAs(): string
    {
        return 'new-feed';
    }
}
