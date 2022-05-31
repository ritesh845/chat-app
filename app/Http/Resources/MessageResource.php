<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ChatroomMessages;
class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public $index;

    public function messageIndex($value){
        $this->index = $value;
        return $this;
    }
    
    public function toArray($request,$index=null)
    {
        $fileurl = '';
        $file_name = '';
        $file_id=0;
        $file_size=0;
        $file_type=0;
        $is_image =false;
        $mimetypes = [
            'image/png',
            'image/jpeg',
            'image/gif',
            'image/bmp',
            'image/svg+xml',
            'image/tiff',
            'image/x-icon'
        ];


        if($this->is_file==1){
            $media = ChatroomMessages::find($this->id);
            if(!empty($media)){
                $file = $media->getMedia();
                if(isset($file[0]))
                {
                    $fileurl = $file[0]->getUrl();
                    $file_id = $file[0]->id;
                    $file_name = $file[0]->file_name;
                    $file_size = $file[0]->human_readable_size;
                    $file_type = $file[0]->mime_type;
                }
                
                $is_image = in_array($file_type, $mimetypes) ? true : false;
            }
        }


        return [
            'message_id'    => $this->id,
            'msg_props'     => $this->msg_props,
            'room_id'       => $this->chatroom_id,
            'room_type'     => $this->chatroom->type,
            'sender_id'     => $this->sender_id,
            'sender_name'   => $this->user->name,
            'message_index' => ($this->index) ? $this->index : '',
            'chatroom_title'=> $this->chatroom->title,
            'message'       => $this->message,
            'is_file'       => ($this->is_file) ? $this->is_file : '0',
            'is_image'      => $is_image,
            'file_id'       => $file_id,
            'file_url'      => $fileurl,
            'file_name'     => $file_name,
            'file_size'     => $file_size,
            'created_at'    => $this->created_at->format('d/m/y h:i A'),
            'updated_at'    => $this->updated_at->format('d-m-y H:i:s'),
            'group_at'      => $this->created_at->format('d-m-y'),
            'message_deleted' => ($this->deleted_at) ? "This message was deleted" : "0",
        ];
    }

    
}
