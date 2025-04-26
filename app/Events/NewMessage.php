<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $sender_type;
    public $sender_id;
    public $delivery_id;
    public $created_at;

    public function __construct(Message $message)
    {
        $this->message = $message->message;
        $this->sender_type = $message->sender_type;
        $this->sender_id = $message->sender_id;
        $this->delivery_id = $message->delivery_id;
        $this->created_at = $message->created_at;
    }

    public function broadcastOn()
    {
        
         return new Channel('delivery.' . $this->delivery_id);
    }
}