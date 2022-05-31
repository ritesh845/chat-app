<?php


namespace App\Http\Repositories;
use App\Http\Repositories\Repository;

class ChatroomMessageRepository extends Repository{
    protected $model;
    protected $model_name = 'App\Models\ChatroomMessages';

    public function __construct()
    {
        parent::__construct();

    }  
    public function getRoomConversations($chatroom_id){
    	$data = $this->model->where('chatroom_id',$chatroom_id)->orderBy('id','desc')->withTrashed()->paginate(20);
    	return $data;
    }

    public function getByIdWithTrashed($id){
        return $this->model->withTrashed()->findOrFail($id);
    }
   
   
}
