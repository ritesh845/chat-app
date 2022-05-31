<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
class UnreadMessage extends Model
{
    public $table = 'unread_messages';
	protected $fillable = [
        'chatroom_id',
        'user_id',
        'message_id',
        'read_at'
    ];

}
