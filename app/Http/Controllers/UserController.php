<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function login(Request $request) {
        return User::where('user_id', $request->user_id)
            ->where('password', $request->password)->first();
    }

    public function register(Request $request) {
        $user = new User();
        $user->user_name = $request->user_name;
        $user->mail_address = $request->mail_address;
        $user->password = $request->password;
        $user->job = $request->job_id;
        $user->profile = $request->profile;
        $result = $user->save();
        return [
            'user_name' => $user->user_name,
        ];
    }
}
