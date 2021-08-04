<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Session;
use Cookie;

class UserController extends Controller
{
    public function login(Request $request) {
        $user = User::where('user_id', $request->user_id)
            ->where('password', $request->password)->first();
        return $user;
    }

    public function register(Request $request) {
        $user = new User();
        $user->user_name = $request->user_name;
        $user->mail_address = $request->mail_address;
        $user->password = $request->password;
        $user->job = $request->job_id;
        $user->profile = $request->profile;
        $result = $user->save();
        $user_ins = User::where('user_name', $request->user_name)
            ->where('password', $request->password)->first();
        return $user_ins;
    }

    public function logout(Request $request) {
        return [
            "user_id" => null,
        ];
    }  
}
