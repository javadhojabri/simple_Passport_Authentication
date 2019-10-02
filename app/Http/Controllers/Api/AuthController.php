<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
   public function register(Request $request)
   {
        $validate_data = $request->validate([
            'name' => 'required|max:55',
            'email'=> 'email|required|unique:users',
            'password'=> 'required|confirmed',
        ]);
        $validate_data['password'] = bcrypt($request->password);
        $user =User::create($validate_data);
        $accessToken = $user->createToken('authToken')->accessToken;
        return response(['user'=>$user, 'accessToken', $accessToken]);
   }
   public function login(Request $request)
   {
   $login_data  = $request->validate([
        'email'=> 'email|required',
        'password'=> 'required',
    ]);
        if  (!auth()->attempt($login_data))
        {
            return response(['message'=>'invalid credentials']);
        }
        $user = $request->user();
        $accessToken =$user->createToken('authToken')->accessToken;
        return response(['user'=>auth()->user(),'accessToken'=>$accessToken]);
   }
}
