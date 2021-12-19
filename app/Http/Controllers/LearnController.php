<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Learn;
use App\Like;
use App\User;
use App\Comment;
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

    public function updLearn(Request $request) {
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

        $learn->learn_id = $request->learn_id;

        Learn::where('learn_id', '=', $request->learn_id)->update([
            'title' => $request->title,
            'detail' => $request->detail,
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
            'language_id' => $request->category_id,
            'learn_datetime_start' => $date_start,
            'learn_datetime_end' => $date_end,
            'color' => $request->color,
        ]);
        return $learn;
    }

    public function delLearn(Request $request) {
        $learn = new Learn();

        $learn->where('learn_id', '=', $request->learn_id)->delete();

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
                ->leftjoin('comments', function ($join) use($user_id) {
                    $join->on('comments.learn_id', '=', 'learns.learn_id')
                      ->where('comments.user_id', '=', $user_id);
                    })
                ->leftjoin('users', 'learns.user_id', '=', 'users.user_id')
                ->select('learns.learn_id', 'learns.title', 'learns.detail', 'users.user_name', 'likes.like_id'
                , 'comments.comment_id', 'comments.comment_content')
                ->where('learns.user_id', $user_id)
                ->get();
        } else {
            $learn = DB::table('learns')
                ->leftjoin('likes', function ($join) use($user_id){
                    $join->on('likes.learn_id', '=', 'learns.learn_id')
                        ->where('likes.user_id', '=', $user_id);
                    })
                ->leftjoin('comments', function ($join) use($user_id) {
                    $join->on('comments.learn_id', '=', 'learns.learn_id')
                      ->where('comments.user_id', '=', $user_id);
                    })
                ->leftjoin('users', 'learns.user_id', '=', 'users.user_id')
                ->select('learns.learn_id', 'learns.title', 'learns.detail', 'users.user_name', 'likes.like_id'
                , 'comments.comment_id', 'comments.comment_content')
                ->where('learns.user_id', $user_id)
                ->where('learns.title', $request->message)->get();
        }
        return $learn;
    }

    public function getLearnsForRanking(Request $request) {
        $user_id = $request->user_id;
        if (empty($request->message)) {
            //$learn = Learn::where('user_id', $request->user_id)->get();
            $learn = DB::table('learns')
                ->leftjoin('likes', function ($join) use($user_id) {
                    $join->on('likes.learn_id', '=', 'learns.learn_id');
                    })
                ->leftjoin('comments', function ($join) use($user_id) {
                    $join->on('comments.learn_id', '=', 'learns.learn_id');
                    })
                ->leftjoin('users', 'learns.user_id', '=', 'users.user_id')
                ->select('learns.learn_id', 'learns.title', 'learns.detail', 'users.user_name'
                    , DB::raw("COALESCE(COUNT(*),0) AS likes_count"))
                ->groupBy('learns.learn_id', 'learns.title', 'learns.detail', 'users.user_name')
                ->where('learns.user_id', $user_id)
                ->get();
        } else {
            $learn = DB::table('learns')
            ->leftjoin('likes', function ($join) use($user_id) {
                $join->on('likes.learn_id', '=', 'learns.learn_id');
                })
            ->leftjoin('comments', function ($join) use($user_id) {
                $join->on('comments.learn_id', '=', 'learns.learn_id');
                })
            ->leftjoin('users', 'learns.user_id', '=', 'users.user_id')
            ->select('learns.learn_id', 'learns.title', 'learns.detail', 'users.user_name'
                , DB::raw("COALESCE(COUNT(*),0) AS likes_count"))
            ->groupBy('learns.learn_id', 'learns.title', 'learns.detail', 'users.user_name')
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
                ->leftjoin('comments', function ($join) use($user_id) {
                    $join->on('comments.learn_id', '=', 'learns.learn_id')
                      ->where('comments.user_id', '=', $user_id);
                    })
                ->leftjoin('users', 'learns.user_id', '=', 'users.user_id')
                ->select('learns.learn_id', 'learns.title', 'learns.detail', 'users.user_name', 'likes.like_id'
                , 'comments.comment_id', 'comments.comment_content')
                ->get();
        } else {
            $learn = DB::table('learns')
                ->leftjoin('likes', function ($join) use($user_id){
                    $join->on('likes.learn_id', '=', 'learns.learn_id')
                        ->where('likes.user_id', '=', $user_id);
                    })
                ->leftjoin('comments', function ($join) use($user_id) {
                    $join->on('comments.learn_id', '=', 'learns.learn_id')
                      ->where('comments.user_id', '=', $user_id);
                    })
                ->leftjoin('users', 'learns.user_id', '=', 'users.user_id')
                ->select('learns.learn_id', 'learns.title', 'learns.detail', 'users.user_name', 'likes.like_id'
                , 'comments.comment_id', 'comments.comment_content')
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
                ->leftjoin('comments', function ($join) use($user_id) {
                    $join->on('comments.learn_id', '=', 'learns.learn_id')
                      ->where('comments.user_id', '=', $user_id);
                    })
                ->leftjoin('users', 'learns.user_id', '=', 'users.user_id')
                ->select('learns.learn_id', 'learns.title', 'learns.detail', 'users.user_name', 'likes.like_id'
                , 'comments.comment_id', 'comments.comment_content')
                ->where('follows.user_id_follow', $user_id)
                ->get();
        } else {
            $learn = DB::table('follows')
                ->leftjoin('learns', 'learns.user_id', '=', 'follows.user_id_follower')
                ->leftjoin('likes', function ($join) use($user_id){
                    $join->on('likes.learn_id', '=', 'learns.learn_id')
                        ->where('likes.user_id', '=', $user_id);
                    })
                ->leftjoin('comments', function ($join) use($user_id) {
                    $join->on('comments.learn_id', '=', 'learns.learn_id')
                      ->where('comments.user_id', '=', $user_id);
                    })
                ->leftjoin('users', 'learns.user_id', '=', 'users.user_id')
                ->select('learns.learn_id', 'learns.title', 'learns.detail', 'users.user_name', 'likes.like_id'
                , 'comments.comment_id', 'comments.comment_content')
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

    public function insComment(Request $request) {
        $comment = new Comment();
        $comment->learn_id = $request->learn_id;
        $comment->user_id = $request->user_id;
        $comment->comment_content = $request->comment_content;
        $result = $comment->save();
        return $comment;
    }

    public function delComment(Request $request) {
        $comment = new Comment();
        $comment->where('comment_id', $request->comment_id)
            ->delete();
        return $comment;
    }
}
