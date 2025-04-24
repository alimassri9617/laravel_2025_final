<?php
// app/Events/NewMessage.php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new \Illuminate\Broadcasting\PrivateChannel('private-delivery.' . $this->message->delivery_id);
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message->message,
            'created_at' => $this->message->created_at,
            'sender_type' => $this->message->sender_type
        ];
    }
}