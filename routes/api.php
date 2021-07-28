<?php

use Illuminate\Http\Request;

use App\Category;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['cors'])->group(function () {
    Route::get('/titles/new', function () {
        return Title::latest()->take(5)->get();
    });

    Route::get('/categories', function () {
        return Category::all();
    });

    Route::get('/category', function (Request $request) {
        return Category::find($request->category_id);
    });

    Route::get('/titles', function (Request $request) {
        return Title::where('category_id', $request->category_id)->paginate(1);
    });

    Route::get('/quiz', function (Request $request) {
        $quiz_id = $request->quiz_id;
        // パラメーターにquiz_idが無い場合はtitle_idから一番最初のquiz_idを取得する
        if(!$quiz_id && $request->title_id) {
            $quiz = Quiz::where('title_id', $request->title_id)->orderBy('id')->first();
            $quiz_id = $quiz->id;
        }
        $quiz = Quiz::find($quiz_id);
        $title = Quiz::find($quiz_id)->title;
        return [
            "id" => $quiz->id,
            "title" => $title->title,
            "question" => $quiz->question,
            "answer1" => $quiz->answer1,
            "answer2" => $quiz->answer2,
            "answer3" => $quiz->answer3,
            "answer4" => $quiz->answer4
        ];
    })->where('quiz_id', '[0-9]+');

    Route::post('/quiz/answer', function (Request $request) {
        // バリデーション
        $validatedData = $request->validate([
            'quiz_id' => 'required|integer',
            'answer' => 'required|between:1,4',
        ]);
        $answer_history = new AnswerHistory();
        $answer_history->quiz_id = $request->quiz_id;
        $answer_history->answer = $request->answer;
        $result = $answer_history->save();
        $quiz = Quiz::find($request->quiz_id);
        $next_quiz_id = getNextQuizId($quiz->title_id, $quiz->id);
        return [
            'answer'       => $quiz->answer,
            'description'  => $quiz->description,
            'next_quiz_id' => $next_quiz_id,
        ];
    });
    Route::options('/categories/1', function () {
        return response()->json();
    });
    Route::post('/categories/1', function (Request $request) {
        // バリデーション
 
        $answer_history = new Category();
        $answer_history->name = $request->name;
        $answer_history->thumbnail = $request->thumbnail;
        $answer_history->sort = $request->sort;
        $result = $answer_history->save();
        return [
            'bbbb'  => $answer_history->name,
            'cccc'  => $answer_history->thumbnail,
            'dddd'  => $answer_history->sort,
        ];
    });

});

// 次のクイズID(無い場合はnull)
function getNextQuizId($title_id, $quiz_id) {
	$is_found = false;
	$quizzes = Quiz::where('title_id', $title_id)->get();
	foreach($quizzes as $quiz) {
        if($is_found) return $quiz->id;
        if($quiz->id === $quiz_id) $is_found = true;
	}
	return null;
}

