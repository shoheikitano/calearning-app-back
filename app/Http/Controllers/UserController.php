<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Follow;
use App\Learn;
use App\Like;
use DateTime;
use Illuminate\Support\Facades\DB;

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
    public function getProfile(Request $request) {
        $profile = User::where('user_id', $request->user_id)
                ->first();
        return $profile;
    }

    public function getFollow(Request $request) {
        if (empty($request->message)) {
            //$learn = Learn::where('user_id', $request->user_id)->get();
            $friends = DB::table('follows')
                ->join('users', 'users.user_id', '=', 'follows.user_id_follow')
                ->where('follows.user_id_follow', $request->user_id)
                ->get();
        } else {
            $friends = DB::table('follows')
                ->join('users', 'users.user_id', '=', 'follows.user_id_follow')
                ->where('follows.user_id_follow', $request->user_id)
                ->where('users.user_name', $request->message)
                ->get();
        }
        return $friends;
    }

    public function getFollower(Request $request) {
        if (empty($request->message)) {
            $friends = DB::table('follows')
                ->join('users', 'users.user_id', '=', 'follows.user_id_follower')
                ->where('follows.user_id_follower', $request->user_id)
                ->get();
        } else {
            $friends = DB::table('follows')
                ->join('users', 'users.user_id', '=', 'follows.user_id_follower')
                ->where('follows.user_id_follower', $request->user_id)
                ->where('users.user_name', $request->message)
                ->get();
        }
        return $friends;
    }

    public function getUsers(Request $request) {
        if (empty($request->message)) {
            $friends = DB::table('users')
                ->leftjoin('follows', 'follows.user_id_follower', '=', 'users.user_id')
                ->where('users.user_id', '<>', $request->user_id)
                ->orderBy('users.user_id')
                ->get();
        } else {
            $friends = DB::table('users')
                ->leftjoin('follows', 'follows.user_id_follower', '=', 'users.user_id')
                ->where('users.user_id', '<>', $request->user_id)
                ->where('users.user_name', $request->message)
                ->orderBy('users.user_id')
                ->get();
        }
        return $friends;
    }

    public function follow(Request $request) {
        $follow = new Follow();
        $follow->user_id_follow = $request->user_id_follow;
        $follow->user_id_follower = $request->user_id;
        $result = $follow->save();
        return $follow;
    }

    public function refollow(Request $request) {
        $follow = new Follow();
        $follow->where('user_id_follow', $request->user_id_follow)
            ->where('user_id_follower', $request->user_id)
            ->delete();
        return $follow;
    }

    public function getLearnsCount(Request $request) {
        $learns_count = Learn::select(DB::raw("COALESCE(COUNT(*),0) AS learns_count"))->where('user_id', $request->user_id)
                ->first();
        return $learns_count;
    }

    public function getLearnsCountInDate(Request $request) {
        $date = new DateTime($request->date);
        $date->format('yyyy-MM-dd'. ' 00:00:00');
        $date2 = new DateTime($request->date);
        $date2->modify('+1 days')->format('yyyy-MM-dd'. ' 00:00:00');
        
        $learns_count = Learn::select(DB::raw("COALESCE(COUNT(*),0) AS learns_count"))
                ->where('user_id', $request->user_id)
                ->where('updated_at','>', $date)
                ->where('updated_at','<', $date2)
                ->first();
        return $learns_count;
    }


    public function getLikesCount(Request $request) {
        $learns_count = DB::table('learns')
                ->join('likes', 'likes.learn_id', '=', 'learns.learn_id')
                ->select(DB::raw("COALESCE(COUNT(*),0) AS likes_count"))
                ->where('learns.user_id', $request->user_id)
                ->get();
        $likes_count;
        foreach ($learns_count as $item) {
            $likes_count = $item->likes_count;
        }
        return [
            "likes_count" => $likes_count,
        ];
    }

    public function getFriendsCount(Request $request) {
        $learns_count = Follow::select(DB::raw("COALESCE(COUNT(*),0) AS friends_count"))->where('user_id_follow', $request->user_id)
                ->first();
        return $learns_count;
    }
}
