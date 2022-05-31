<?php


namespace App\Http\Repositories;
use App\Http\Repositories\Repository;

class UnreadMessageRepository extends Repository{
    protected $model;
    protected $model_name = 'App\Models\UnreadMessage';

    public function __construct()
    {
        parent::__construct();

    }  
    
    public function readAtUpdate($room_id){
    	$this->model->where('chatroom_id', $room_id)->where('user_id', auth()->user()->id)->update(['read_at'=>date('Y-m-d H:i:s')]);
    }
}
