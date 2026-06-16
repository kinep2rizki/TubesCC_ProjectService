<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LiveAttendanceUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $eventId;
    public $presentCount;
    public $expectedCount;

    public function __construct($eventId, $presentCount, $expectedCount)
    {
        $this->eventId = $eventId;
        $this->presentCount = $presentCount;
        $this->expectedCount = $expectedCount;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('event.' . $this->eventId . '.attendance'),
        ];
    }
    
    public function broadcastAs(): string
    {
        return 'attendance-updated';
    }
}
