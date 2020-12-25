<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => 'api'], function(){
    Route::resource('test', 'API\TestController');
    // Route::resource('blogs', 'API\BlogController');

    // ブログ複数取得
    Route::get('blogs', 'API\BlogController@list');

    // 記事複数取得
    Route::get('blogs/{blog}/articles', 'API\ArticleController@list');

});
