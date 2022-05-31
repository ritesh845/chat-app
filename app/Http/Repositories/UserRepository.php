<?php


namespace App\Http\Repositories;
use App\Http\Repositories\Repository;
use Illuminate\Support\Facades\Auth;

class UserRepository extends Repository{
    protected $model;
    protected $model_name = 'App\Models\User';


    public function __construct()
    {
        parent::__construct();

    }
    public function getChatUsers($chatroom_users){
       $data = $this->model->where('id','!=',Auth::user()->id)->whereNotIn('id',$chatroom_users)->get();
       return $data;
    }

    public function getAllUsers(){
        $data = $this->model->where('id','!=',Auth::user()->id)->get();
        return $data;
    }

  
}
