<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $sender_id;
    public string $message;
    public int $order_id;

    /**
     * Create a new event instance.
     */
    public function __construct(int $sender_id, string $message, int $order_id)
    {
        $this->sender_id = $sender_id;
        $this->message = $message;
        $this->order_id = $order_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return [new Channel('private-conversations.'.$this->order_id)];
        // return new PrivateChannel('private' . $this->order_id);
    }

    public function broadcastAs()
    {
        return 'chat';
    }

    // public function broadcastWith()
    // {
    //     return [
    //         'user' => $this->sender_id,
    //         'message' => $this->message,
    //     ];
    // }
}
