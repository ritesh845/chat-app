<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('authenticate',[App\Http\Controllers\Auth\AuthenticateController::class,'authenticate']);

Route::group(['middleware' => ['auth:api']], function () {
	Route::post('logout', [App\Http\Controllers\Auth\AuthenticateController::class,'logout']);

	Route::post('/new-private-chat',[App\Http\Controllers\HomeController::class,'newPrivatechat']);
	Route::post('create-new-group',[App\Http\Controllers\HomeController::class,'createNewGroup']);
	

	Route::get('/get-chat-users',[App\Http\Controllers\HomeController::class,'getChatUsers']);
	Route::get('/get-all-users',[App\Http\Controllers\HomeController::class,'getAllUsers']);
	Route::get('/get-user-chatrooms',[App\Http\Controllers\HomeController::class,'getUserChatrooms']);
	Route::post('/get-room-conversations',[App\Http\Controllers\HomeController::class,'getRoomConversations']);
	Route::get('/get-room-details/{id}',[App\Http\Controllers\HomeController::class,'getRoomDetails']);

	Route::post('/send-message',[App\Http\Controllers\HomeController::class,'sendMessage']);
	Route::post('/edit-message',[App\Http\Controllers\HomeController::class,'editMessage']);
	Route::post('/delete-message',[App\Http\Controllers\HomeController::class,'deleteMessage']);
	Route::post('/upload-file',[App\Http\Controllers\HomeController::class,'uploadFile']);
	
	Route::get('/read-messages/{id}',[App\Http\Controllers\HomeController::class,'readMessages']);

});
Route::get('/download-file/{id}',[App\Http\Controllers\HomeController::class,'downloadfile']);