<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\ContactCollection;
use App\Http\Resources\ParticipantCollection;
use App\Http\Resources\MessageResource;
use App\Http\Resources\MessageResourceCollection;
use App\Http\Resources\GeneralResponse;
use App\Http\Resources\GeneralError;

use App\Models\User;
use App\Models\Chatrooms;
use App\Models\ChatroomMessages;
use App\Models\ChatroomUser;

use DB;
use Log;
use Auth;
use Carbon\Carbon;

use App\Http\Repositories\UserRepository;
use App\Http\Repositories\ChatroomRepository;
use App\Http\Repositories\ChatroomUserRepository;
use App\Http\Repositories\ChatroomMessageRepository;
use App\Http\Repositories\UnreadMessageRepository;

use Illuminate\Support\Facades\Storage;
use App\Events\NewRoom;
use App\Events\NewMessage;
use App\Events\EditMessage;
use App\Events\DeleteMessage;

class HomeController extends Controller
{
    protected $userRepo,$chatroomRepo,$chatroomUserRepo,$chatroomMessRepo,$unreadMsgRepo;

    public function __construct(UserRepository $userRepo, ChatroomRepository $chatroomRepo,ChatroomUserRepository $chatroomUserRepo, ChatroomMessageRepository $chatroomMessRepo,UnreadMessageRepository $unreadMsgRepo){
        $this->userRepo = $userRepo;
        $this->chatroomRepo = $chatroomRepo;
        $this->chatroomUserRepo = $chatroomUserRepo;
        $this->chatroomMessRepo = $chatroomMessRepo;
        $this->unreadMsgRepo    = $unreadMsgRepo;
    }

   
    public function newPrivatechat(Request $request){
    	$auth_user_id = Auth::user()->id;
    	DB::beginTransaction();

        try {

            $data = [ 
                'title' =>  'New Chat Room',
                'type'  => 'private'
            ];
            $data['initiator_id'] = $auth_user_id;
            $users = [
                $auth_user_id,
                $request->user_id,
            ];

            $chatroom = $this->chatroomRepo->create($data);
            $chatroom->chatroomUser()->sync($users);

            DB::commit();
            return  new GeneralResponse(['data'=>[],'message'=>"New Chat Room Saved Successfully"]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            \Log::info($e);
            return new GeneralError(['code'=>500,'message' => 'Something Went Wrong', 'toast' => true, 'data' => array()]);

        }


    }

    public function getChatUsers(){
                            
        $cUserRoom =  $this->chatroomUserRepo->getPrivateChatroomsIds();
    	$cRoom = $this->chatroomRepo->getChatroomsIds();
        $chatroom_ids = array_merge($cUserRoom->toArray(),$cRoom->toArray());

    	$chatroom_users = $this->chatroomUserRepo->getUserIds($chatroom_ids); 

    	$users = $this->userRepo->getChatUsers($chatroom_users);
        return (new ContactCollection($users));
    }

    public function getAllUsers(){
        $users = $this->userRepo->getAllUsers();
        return (new ContactCollection($users));
    }

    public function getUserChatrooms(){
        $crooms = $this->chatroomRepo->getUserChatrooms();
        $data =  (new ParticipantCollection($crooms));
        $datas = collect($data)->sortByDesc('last_message_time');

        return new GeneralResponse(['data' => $datas,'message' => 'Reterived successfully']);
    } 

    public function getRoomConversations(Request $request){
        $data = $this->chatroomMessRepo->getRoomConversations($request->chatroom_id);
        return (new MessageResourceCollection($data))->chatroom_id($request->chatroom_id);


      //  return new GeneralResponse(['data' => $data,'message' => 'user reterived successfully']);

    }

    public function getRoomDetails($id){
        $crooms = $this->chatroomRepo->getRoomDetails($id);
        return (new ParticipantCollection($crooms));
    }

    public function sendMessage(Request $request){
        $user_id = Auth::user()->id;

        $msg_props = array(
            'parent_id' => "",
            'sender_id' => "",
            'sender_name' => "",
            'msg' => "",
            'quoted'=> "",
            'edited'=> ""
        );
        DB::beginTransaction();

        try {

            $newMessage['chatroom_id'] = $request->chatroom_id;
            $newMessage['sender_id']   = $user_id;
            $newMessage['message']     = $request->message;
            $newMessage['is_file']     = 0;
            $newMessage['msg_props']   = json_encode($msg_props);

            $newMessage = $this->chatroomMessRepo->create($newMessage);
            $this->unreadMessageCreate($request,$newMessage,$user_id);
            broadcast(new NewMessage($newMessage));

            DB::commit();
           
            return (new MessageResource($newMessage));
        }
        catch (\Exception $e) {
            DB::rollBack();
            \Log::info($e);
            return new GeneralError(['code'=>500,'message' => 'Something Went Wrong', 'toast' => true, 'data' => array()]);

        }
    }

    public function editMessage(Request $request){
        $editMessage = $this->chatroomMessRepo->getById($request->message_id);
        $msg_props  =  json_decode($editMessage->msg_props);
        $msg_props->edited = true;
        $edited['message'] = $request->message;
        $edited['msg_props'] = json_encode($msg_props);
        $edited = $this->chatroomMessRepo->update($request->message_id,$edited);
        broadcast(new EditMessage($edited,$request->index));
    }

    public function deleteMessage(Request $request){
        $this->chatroomMessRepo->delete($request->message_id);
        $deleteMessage = $this->chatroomMessRepo->getByIdWithTrashed($request->message_id);

        $media = DB::table('media')->where('model_id', $request->message_id)->first();
        if(!empty($media)){
            Storage::disk('media')->delete($media->id . '/' . $media->file_name);
            DB::table('media')->where('model_id', $request->message_id)->delete();
        }
        
        broadcast(new DeleteMessage($deleteMessage,$request->index));
    }



    public function createNewGroup(Request $request){
        $auth_user_id = Auth::user()->id;
        DB::beginTransaction();

        try {
            $data = [ 
                'title' =>  $request->group_name,
                'type'  => 'group',
                'initiator_id' => $auth_user_id
            ];
            $chatroom = $this->chatroomRepo->create($data);
            $users= array_merge([$auth_user_id],$request->userIds);
            $chatroom->chatroomUser()->sync($users);
            // return $data;
            DB::commit();
            return  new GeneralResponse(['data'=>[],'message'=>"New Chat Room Saved Successfully"]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            \Log::info($e);
            return new GeneralError(['code'=>500,'message' => 'Something Went Wrong', 'toast' => true, 'data' => array()]);
        }
    }


    public function readMessages($room_id) {
        $this->unreadMsgRepo->readAtUpdate($room_id);
    }

    public function uploadFile(Request $request){
        
        $user_id = auth()->user()->id;
        DB::beginTransaction();
        try {
            $file = $request->file('file');
            $name = $file->getClientOriginalName();

            $newMessage['chatroom_id'] = $request->chatroom_id;
            $newMessage['sender_id']   = $user_id;
            $newMessage['is_file']     = 1;

            $newMessage = $this->chatroomMessRepo->create($newMessage);

            $newMessage->addMedia($request->file)->usingFileName($name)->toMediaCollection();
            $this->unreadMessageCreate($request,$newMessage,$user_id);
            DB::commit();
            broadcast(new NewMessage($newMessage));
            return (new MessageResource($newMessage));
        }
        catch (\Exception $e) {
            DB::rollBack();
            \Log::info($e);
            return new GeneralError(['code'=>500,'message' => 'Something Went Wrong', 'toast' => true, 'data' => array()]);

        }


    }

    public function unreadMessageCreate($request,$newMessage,$user_id){
        $chatroom_users = $this->chatroomUserRepo->getUserIds([$request->chatroom_id]); 
            foreach ($chatroom_users as $key => $value) {
                if ($user_id != $value) {
                    $unreadMessage = [
                        'chatroom_id' => $request->chatroom_id,
                        'user_id' => $value,
                        'message_id' => $newMessage->id
                    ];
                    $this->unreadMsgRepo->create($unreadMessage);
                }
            }
    }

    public function downloadfile($id){
        $messge = $this->chatroomMessRepo->getById($id);
        return response()->download($messge->getMedia()[0]->getPath(), $messge->getMedia()[0]->file_name);
    }

}
