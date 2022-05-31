<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class ChatroomUser extends Model
{
    public $table = 'chatroom_user';

	protected $fillable = [
        'chatroom_id',
        'user_id'
    ];
    protected $primaryKey = null;
    public $timestamps = false;

    public function user(){
    	return $this->hasOne('App\Models\User','id','user_id');
    }
    public function chatroom(){
        return $this->hasOne('App\Models\Chatrooms','id','chatroom_id');
    }

}
