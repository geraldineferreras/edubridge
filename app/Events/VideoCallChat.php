<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoCallChat implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $message;
    public $room_id;

    public function __construct($user, $message, $room_id)
    {
        $this->user = $user;
        $this->message = $message;
        $this->room_id = $room_id;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('video-room.' . $this->room_id);
    }

    public function broadcastAs()
    {
        return 'VideoCallChat';
    }
}

