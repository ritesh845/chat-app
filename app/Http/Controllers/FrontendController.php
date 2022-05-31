<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index(){
        return view('chat.home');
    }

    public function chatroom($chatroom_id){

        return view('chat.components.chatroom',compact('chatroom_id'));
    }  
}
