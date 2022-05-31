<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\GeneralResponse;
class AuthenticateController extends Controller
{
    public function authenticate(Request $request){
        $data = $request->all();
        $rules = [
            'email' => ['required', 'email', 'string'],
            'password' => ['required']
        ];
        $messages = [
            'email.required' => 'Email is required',
            'email.email' => 'Invalid Email',
            'email.string' => 'Invalid Email',
            'password.required' => 'Password is required',
        ];
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()], 400);
        }
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $success = [];
            $user = Auth::user();
            $success['user'] = User::where('id',Auth::id())->get()->first();
            $resultToken = $user->createToken($user->name);
            $token = $resultToken->token;
            $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();
            $success['token'] = $resultToken->accessToken;
            $success['expired_at'] = $token->expires_at;

            $success['message'] = "Successful Login";
            return  response()->json(['data' => $success],200);
        } else {
            return  response()->json(['errors' => 'Invalid Credentials'],401);;
        }
    }

    public function logout(Request $request) {

        if (Auth::check()) {
            Auth::user()->save();
            Auth::user()->token()->revoke();
            return new GeneralResponse(['message' => 'logout successful']);
        } else {
            return response()->json(['error' => 'api.something_went_wrong'], 500);
        }
    }
}
