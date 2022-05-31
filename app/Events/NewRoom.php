<?php

namespace App\Events;
use App\Models\ChatroomUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class NewRoom implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ChatroomUser $room)
    {
        $this->room = $room;
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('newroomcreated.' . $this->room->user_id);
    }

    public function broadcastWith()
    {
        $this->room->load('chatroom');
        return ["newroomdata" => $this->room];
    }
}
