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
    Route::options('/login', function () {
        return response()->json();
    });
    Route::post('/login', 'UserController@login');

    Route::options('/logout', function () {
        return response()->json();
    });
    Route::post('/logout', 'UserController@logout');

    Route::options('/register', function () {
        return response()->json();
    });
    Route::post('/register', 'UserController@register');

    Route::options('/learn', function () {
        return response()->json();
    });
    Route::post('/learn', 'LearnController@register');

    Route::options('/updLearn', function () {
        return response()->json();
    });
    Route::post('/updLearn', 'LearnController@updLearn');

    Route::options('/delLearn', function () {
        return response()->json();
    });
    Route::post('/delLearn', 'LearnController@delLearn');

    Route::get('/calearning', 'LearnController@calearning');

    Route::get('/learns', 'LearnController@getLearns');

    Route::get('/getLearn', 'LearnController@getLearn');

    Route::get('/followlearns', 'LearnController@getFollowLearns');

    Route::get('/alllearns', 'LearnController@getAllLearns');

    Route::get('/getProfile', 'UserController@getProfile');

    Route::get('/getFollow', 'UserController@getFollow');

    Route::get('/getFollower', 'UserController@getFollower');

    Route::get('/getUsers', 'UserController@getUsers');

    Route::options('/like', function () {
        return response()->json();
    });

    Route::post('/like', 'LearnController@like');

    Route::options('/relike', function () {
        return response()->json();
    });

    Route::post('/relike', 'LearnController@reLike');

    Route::options('/follow', function () {
        return response()->json();
    });

    Route::post('/follow', 'UserController@follow');

    Route::options('/refollow', function () {
        return response()->json();
    });

    Route::post('/refollow', 'UserController@refollow');

    Route::get('/getLearnsCount', 'UserController@getLearnsCount');

    Route::get('/getLikesCount', 'UserController@getLikesCount');

    Route::get('/getFriendsCount', 'UserController@getFriendsCount');
});
