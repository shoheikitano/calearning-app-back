<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Learn;
use App\Like;
use App\User;
use DateTime;
use Illuminate\Support\Facades\DB;

class LearnController extends Controller
{
    //
    public function register(Request $request) {
        $learn = new Learn();
        $learn->title = $request->title;
        $learn->detail = $request->detail;
        $learn->user_id = $request->user_id;
        $learn->category_id = $request->category_id;
        $learn->language_id = $request->language_id;
        
        $date_start = new DateTime($request->learn_datetime_start);
        $date_start->format('yyyy-MM-dd HH:mm:ss');
        $learn->learn_datetime_start = $date_start;
        
        $date_end = new DateTime($request->learn_datetime_end);
        $date_end->format('yyyy-MM-dd HH:mm:ss');
        $learn->learn_datetime_end = $date_end;

        $learn->color = $request->color;

        $result = $learn->save();
        return $learn;
    }

    public function calearning(Request $request) {
        $learn = Learn::select('title AS name', 'learn_datetime_start as start'
        , 'learn_datetime_end as end', 'color', 'learn_id')->where('user_id', $request->user_id)->get();
        return $learn;
    }

    public function getLearn(Request $request) {
        $learn = Learn::where('learn_id', $request->learn_id)->first();
        return $learn;
    }

    public function getLearns(Request $request) {
        $user_id = $request->user_id;
        if (empty($request->message)) {
            //$learn = Learn::where('user_id', $request->user_id)->get();
            $learn = DB::table('learns')
                ->leftjoin('likes', function ($join) use($user_id) {
                    $join->on('likes.learn_id', '=', 'learns.learn_id')
                      ->where('likes.user_id', '=', $user_id);
                    })
                ->leftjoin('retweets', function ($join) use($user_id) {
                    $join->on('retweets.learn_id', '=', 'learns.learn_id')
                      ->where('retweets.user_id', '=', $user_id);
                    })
                ->leftjoin('comments', function ($join) use($user_id) {
                    $join->on('comments.learn_id', '=', 'learns.learn_id')
                      ->where('comments.user_id', '=', $user_id);
                    })
                ->leftjoin('users', 'learns.user_id', '=', 'users.user_id')
                ->select('learns.learn_id', 'learns.title', 'learns.detail', 'users.user_name', 'likes.like_id'
                , 'retweets.retweet_id', 'comments.comment_id')
                ->where('learns.user_id', $user_id)
                ->get();
        } else {
            $learn = DB::table('learns')
                ->leftjoin('likes', function ($join) use($user_id){
                    $join->on('likes.learn_id', '=', 'learns.learn_id')
                        ->where('likes.user_id', '=', $user_id);
                    })
                ->leftjoin('retweets', function ($join) use($user_id) {
                    $join->on('retweets.learn_id', '=', 'learns.learn_id')
                      ->where('retweets.user_id', '=', $user_id);
                    })
                ->leftjoin('comments', function ($join) use($user_id) {
                    $join->on('comments.learn_id', '=', 'learns.learn_id')
                      ->where('comments.user_id', '=', $user_id);
                    })
                ->leftjoin('users', 'learns.user_id', '=', 'users.user_id')
                ->select('learns.learn_id', 'learns.title', 'learns.detail', 'users.user_name', 'likes.like_id'
                , 'retweets.retweet_id', 'comments.comment_id')
                ->where('learns.user_id', $user_id)
                ->where('learns.title', $request->message)->get();
        }
        return $learn;
    }

    public function getAllLearns(Request $request) {
        $user_id = $request->user_id;
        if (empty($request->message)) {
            //$learn = Learn::where('user_id', $request->user_id)->get();
            $learn = DB::table('learns')
                ->leftjoin('likes', function ($join) use($user_id) {
                    $join->on('likes.learn_id', '=', 'learns.learn_id')
                      ->where('likes.user_id', '=', $user_id);
                    })
                ->leftjoin('retweets', function ($join) use($user_id) {
                    $join->on('retweets.learn_id', '=', 'learns.learn_id')
                      ->where('retweets.user_id', '=', $user_id);
                    })
                ->leftjoin('comments', function ($join) use($user_id) {
                    $join->on('comments.learn_id', '=', 'learns.learn_id')
                      ->where('comments.user_id', '=', $user_id);
                    })
                ->leftjoin('users', 'learns.user_id', '=', 'users.user_id')
                ->select('learns.learn_id', 'learns.title', 'learns.detail', 'users.user_name', 'likes.like_id'
                , 'retweets.retweet_id', 'comments.comment_id')
                ->get();
        } else {
            $learn = DB::table('learns')
                ->leftjoin('likes', function ($join) use($user_id){
                    $join->on('likes.learn_id', '=', 'learns.learn_id')
                        ->where('likes.user_id', '=', $user_id);
                    })
                ->leftjoin('retweets', function ($join) use($user_id) {
                    $join->on('retweets.learn_id', '=', 'learns.learn_id')
                      ->where('retweets.user_id', '=', $user_id);
                    })
                ->leftjoin('comments', function ($join) use($user_id) {
                    $join->on('comments.learn_id', '=', 'learns.learn_id')
                      ->where('comments.user_id', '=', $user_id);
                    })
                ->leftjoin('users', 'learns.user_id', '=', 'users.user_id')
                ->select('learns.learn_id', 'learns.title', 'learns.detail', 'users.user_name', 'likes.like_id'
                , 'retweets.retweet_id', 'comments.comment_id')
                ->where('title', $request->message)->get();
        }
        return $learn;
    }

    public function getFollowLearns(Request $request) {
        $user_id = $request->user_id;
        if (empty($request->message)) {
            //$learn = Learn::where('user_id', $request->user_id)->get();
            $learn = DB::table('follows')
                ->leftjoin('learns', 'learns.user_id', '=', 'follows.user_id_follower')
                ->leftjoin('likes', function ($join) use($user_id) {
                    $join->on('likes.learn_id', '=', 'learns.learn_id')
                      ->where('likes.user_id', '=', $user_id);
                    })
                ->leftjoin('retweets', function ($join) use($user_id) {
                    $join->on('retweets.learn_id', '=', 'learns.learn_id')
                      ->where('retweets.user_id', '=', $user_id);
                    })
                ->leftjoin('comments', function ($join) use($user_id) {
                    $join->on('comments.learn_id', '=', 'learns.learn_id')
                      ->where('comments.user_id', '=', $user_id);
                    })
                ->leftjoin('users', 'learns.user_id', '=', 'users.user_id')
                ->select('learns.learn_id', 'learns.title', 'learns.detail', 'users.user_name', 'likes.like_id'
                , 'retweets.retweet_id', 'comments.comment_id')
                ->where('follows.user_id_follow', $user_id)
                ->get();
        } else {
            $learn = DB::table('follows')
                ->leftjoin('learns', 'learns.user_id', '=', 'follows.user_id_follower')
                ->leftjoin('likes', function ($join) use($user_id){
                    $join->on('likes.learn_id', '=', 'learns.learn_id')
                        ->where('likes.user_id', '=', $user_id);
                    })
                ->leftjoin('retweets', function ($join) use($user_id) {
                    $join->on('retweets.learn_id', '=', 'learns.learn_id')
                      ->where('retweets.user_id', '=', $user_id);
                    })
                ->leftjoin('comments', function ($join) use($user_id) {
                    $join->on('comments.learn_id', '=', 'learns.learn_id')
                      ->where('comments.user_id', '=', $user_id);
                    })
                ->leftjoin('users', 'learns.user_id', '=', 'users.user_id')
                ->select('learns.learn_id', 'learns.title', 'learns.detail', 'users.user_name', 'likes.like_id'
                , 'retweets.retweet_id', 'comments.comment_id')
                ->where('follows.user_id_follow', $user_id)
                ->where('learns.title', $request->message)->get();
        }
        return $learn;
    }

    public function like(Request $request) {
        $like = new Like();
        $like->learn_id = $request->learn_id;
        $like->user_id = $request->user_id;
        $result = $like->save();
        return $like;
    }

    public function reLike(Request $request) {
        $like = new Like();
        $like->where('learn_id', $request->learn_id)
            ->where('user_id', $request->user_id)
            ->delete();
        return $like;
    }
}
