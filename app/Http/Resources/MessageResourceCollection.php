<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\ChatroomMessages;
class MessageResourceCollection extends ResourceCollection
{
    
    protected $chatroom_id;

    public function chatroom_id($value){
        $this->chatroom_id = $value;
        return $this;
    }

    public function toArray($request)
    {
        return parent::toArray($request);
    }
    public function with($request)
    {
        
        return [
            'meta' => [
                'sayhi' => ChatroomMessages::where('chatroom_id',$this->chatroom_id)->where('sender_id',auth()->user()->id)->count(),
            ],
        ];
    }
}
