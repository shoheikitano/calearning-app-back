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

    Route::options('/register', function () {
        return response()->json();
    });
    Route::post('/register', 'UserController@register');
});
