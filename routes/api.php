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

    // ブログAPI
    // 有効ブログ複数取得
    Route::get('blogs', 'API\BlogController@list');

    // 記事API
    // 有効記事複数取得
    Route::get('blogs/{blog}/articles', 'API\ArticleController@list');

    // ユーザーAPI
    // ユーザー取得
    Route::get('users/{user}', 'API\UserController@get');

    // コメントAPI
    // 記事idから有効コメント複数取得
    Route::get('articles/{article}/comments', 'API\CommentController@list');

    // お気に入りAPI
    // 記事idから有効お気に入り複数取得
    Route::get('articles/{article}/favorites', 'API\FavoriteController@list');
    // 記事idとユーザーidから、記事にあるお気に入りの中からユーザーidに該当するものがあるかどうか判定;
    Route::get('articles/{article}/favorites/exists', 'API\FavoriteController@existUserOnArticle');

});
