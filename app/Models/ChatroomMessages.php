<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ChatroomMessages;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ChatroomMessages extends Model implements HasMedia
{
   	public $table = 'chatroom_messages';
    
    use SoftDeletes,InteractsWithMedia;
	protected $fillable = [
        'chatroom_id',
        'sender_id',
        'message',
        'is_file',
        'msg_props'
    ];
    public function user() {
        return $this->belongsTo('App\Models\User', 'sender_id', 'id');
    }
    public function chatroom() {
        return $this->belongsTo('App\Models\Chatrooms');
    }
    public function messages() {
        return $this->hasMany(ChatroomMessages::class);
    }
}
