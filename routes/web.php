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
Route::get('/home', 'HomeController@index')->name('home');



Route::namespace('User')->prefix('users')->name('users.')->group(function () {
    // ログイン認証関連
    Auth::routes();

    // アカウント作成確認画面
    Route::get('register/confirm','Auth\RegisterController@confirm')->name('register.confirm');
    Route::post('register/confirm','Auth\RegisterController@post');

    // プロフィール設定
    Route::get('edit', 'ProfilesController@index')->name('profile.edit');
    Route::post('edit/update', 'ProfilesController@update')->name('profile.update');

    // twitterログイン
    Route::get('login/twitter', 'Auth\LoginController@redirectToTwitterProvider')->name('login.twitter');
    Route::get('login/twitter/callback', 'Auth\LoginController@handleTwitterProviderCallback')->name('login.twitter.callback'); //ここのコールバック統一できそう

    // facebookログイン
    Route::get('/login/facebook', 'Auth\LoginController@redirectToFacebookProvider')->name('login.facebook');
    Route::get('/login/facebook/callback', 'Auth\LoginController@handleFacebookProviderCallback')->name('login.facebook.callback');   //ここのコールバック統一できそう
});



Route::prefix('users')->name('users.')->group(function(){
    //ブログ管理
    Route::resource('{user}/blogs', 'BlogController',['only' => ['index']])->middleware('filterBy.routeParameters');   //ここのuserパラメータいらない。
    Route::resource('{user}/blogs', 'BlogController',['only' => ['show','edit','update','destroy']])->middleware('filterBy.routeParameters:blog');

    Route::prefix('{user}/blogs')->name('blogs.')->group(function(){
        //記事管理
        Route::resource('{blog}/articles', 'ArticleController',['only' => ['create','store']])->middleware('filterBy.routeParameters:blog');
        Route::resource('{blog}/articles', 'ArticleController',['only' => ['show','edit','update','destroy']])->middleware('filterBy.routeParameters:article');

        Route::prefix('{blog}/articles')->name('articles.')->group(function(){
            //コメント管理
            Route::resource('{article}/comments', 'CommentController',['only' => ['store']])->middleware('filterBy.routeParameters:article');
            Route::resource('{article}/comments', 'CommentController',['only' => ['destroy']])->middleware('filterBy.routeParameters:comment');
        });
    });
});