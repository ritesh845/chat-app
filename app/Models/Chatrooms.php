<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Chatrooms extends Model
{
	public $table = 'chatrooms';
	protected $fillable = [
        'title',
        'initiator_id',
        'icon',
        'type'
    ];

    public function participants() {
        return $this->hasMany(ChatroomUser::class, 'chatroom_id', 'id');
    }

    public function chatroomUser() {
        return $this->belongsToMany(User::class, 'chatroom_user', 'chatroom_id', 'user_id');
    }

}
