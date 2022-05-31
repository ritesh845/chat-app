<?php


namespace App\Http\Repositories;
use App\Http\Repositories\Repository;

class ChatroomUserRepository extends Repository{
    protected $model;
    protected $model_name = 'App\Models\ChatroomUser';

    public function __construct()
    {
        parent::__construct();

    }  
    public function getUserIds($chatroom_ids){
    	$data = $this->model->whereIn('chatroom_id',$chatroom_ids)->pluck('user_id');
    	return $data;
    }
    public function getPrivateChatroomsIds(){
        $data =  $this->model->whereHas('chatroom',function($q){
            return $q->where('type','private');
        })->where('user_id',auth()->user()->id)->pluck('chatroom_id');
        return $data;
    }

}
