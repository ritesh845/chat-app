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

use App\Models\ChatroomMessages;
use App\Http\Resources\MessageResource;

class DeleteMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;
    public $index;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ChatroomMessages $message, $index)
    {
        $this->message = $message;
        $this->index = $index;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('deleteMessage.' . $this->message->chatroom_id);
    }

    public function broadcastWith()
    {
        return ["deletedmessage" => (new MessageResource($this->message))->messageIndex($this->index)];
    }
}
