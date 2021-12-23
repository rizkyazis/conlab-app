<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/run/python','API\RunController@runPython')->name('run.python');

Route::prefix('score')->middleware('auth:web')->name('score.')->group(function(){
    Route::get('/languages', 'API\ScoreController@getLanguages')->name('languages');
    Route::get('/courses', 'API\ScoreController@getCourses')->name('courses');
    Route::get('/sections', 'API\ScoreController@getSections')->name('sections');
    Route::get('/lessons', 'API\ScoreController@getLessons')->name('lessons');
});
