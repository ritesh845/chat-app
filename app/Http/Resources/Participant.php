<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ChatroomMessages;
use App\Models\UnreadMessage;
use Carbon\Carbon;
class Participant extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
   
    public function toArray($request)
    {
        $user_name="";
        $user_id="";
        if($this->type == 'private'){
            foreach ($this->participants as $user) {
                if($user->user_id != auth()->user()->id){
                    $user_name = $user->user->name;
                    $user_id   = $user->user_id;
                }
            }
        }else{
            $user_name = $this->title;
        }

        $lastMessage = ChatroomMessages::where('chatroom_id',$this->id)->orderBy('id','desc')->first();

        // Convert display date according to difference between created at and Date()

        //If there is zero messages then latest date is null so it will featch date on whic group has created

        $lastMessage_time =  !empty($lastMessage) ? Carbon::parse($lastMessage['created_at']) : Carbon::parse($this->created_at); 
        $date1 = Carbon::parse($lastMessage_time);
        $now = Carbon::parse(Carbon::now()->format('Y-m-d'));
        $diff = $now->diffInDays(Carbon::parse($date1->format('Y-m-d')));
        if($diff==0){
            $display_date = $lastMessage_time->format("h:i A");
        }else if($diff >= 2){
            $display_date = $lastMessage_time->format("d/m/y");
        }else{
            $display_date = 'Yesterday';
        }

        $unreadcount = UnreadMessage::where([
            'chatroom_id' => $this->id,
            'user_id' => auth()->user()->id,
            'read_at' => null
        ])->count();


        return [
            'room_id'         => $this->id,
            'user_id'         => $user_id,
            'room_type'       => $this->type,
            'room_name'       => $user_name,
            'initiator_id'    => $this->initiator_id,
            'user_avatar'     => $this->icon,
            'typing'          => false,
            'participants'    => collect($this->participants)->count(),
            'unread_count'    => $unreadcount,
            'last_message'    => !empty($lastMessage) ? $lastMessage['message'] : "",
            'last_message_time' => !empty($lastMessage) ? $lastMessage['created_at']->format('Y-m-d H:i:s') : $this->created_at->format('Y-m-d H:i:s'),
            'display_last_message_time' => $display_date
        ];

    }   
}
