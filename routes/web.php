<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test/model/{index}','TestController@index');

Route::namespace('User')->prefix('users')->name('users.')->group(function () {
    // ログイン認証関連
    Auth::routes();

    // アカウント作成確認画面
    Route::get('register/confirm','Auth\RegisterController@confirm')->name('register.confirm');
    Route::post('register/confirm','Auth\RegisterController@post');

    // プロフィール設定
    Route::get('edit', 'ProfilesController@index')->name('profile.edit');
    Route::post('edit/update', 'ProfilesController@update')->name('profile.update');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('users')->name('users.')->group(function(){
    //ブログ管理
    Route::resource('{user}/blogs', 'BlogController',['only' => ['index','show','edit','update','destroy']]);

    Route::prefix('{user}/blogs')->name('blogs.')->group(function(){
        //記事管理
        Route::resource('{blog}/articles', 'ArticleController');

        Route::prefix('{blog}/articles')->name('articles.')->group(function(){
            //コメント管理
            Route::resource('{article}/comments', 'CommentController');
        });
    });
});