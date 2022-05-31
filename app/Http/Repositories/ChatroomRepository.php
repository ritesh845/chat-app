<?php


namespace App\Http\Repositories;
use App\Http\Repositories\Repository;
use Illuminate\Support\Facades\Auth;

class ChatroomRepository extends Repository{
    protected $model;
    protected $model_name = 'App\Models\Chatrooms';

    public function __construct()
    {
        parent::__construct();

    }  
    public function getChatroomsIds(){
    	$data = $this->model->where('initiator_id',Auth::user()->id)->pluck('id');
    	return $data;
    }
    
    public function getUserChatrooms(){
        $data  = $this->model->whereHas('participants', function ($query)  {
                $query->where('user_id', '=', Auth::user()->id);
            })
            ->with('participants.user')
            ->get();
        return $data;
    }
    public function getRoomDetails($id){
        $data = $this->model->with('participants')->where('id',$id)->get();
        return $data;
    }

  
}
