<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Learn;
use DateTime;

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

        $result = $learn->save();
        return $learn;
    }
}
