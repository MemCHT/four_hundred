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


// 一般機能
Route::prefix('users')->name('users.')->group(function(){
    //ヘッダーの検索機能 ※Blog/ArticleControllerへリダイレクト
    Route::get('search', 'HeaderController@search')->name('header.search');

    //ブログ管理
    Route::resource('{user}/blogs', 'BlogController',['only' => ['index']])->middleware('filterBy.routeParameters');
    Route::resource('{user}/blogs', 'BlogController',['only' => ['show','edit','update','destroy']])->middleware('filterBy.routeParameters:blog');

    //フォロー管理
    Route::resource('{user}/follows', 'FollowController', ['only' => ['store', 'destroy']]);

    Route::prefix('{user}/blogs')->name('blogs.')->group(function(){
        //記事管理
        Route::resource('{blog}/articles', 'ArticleController',['only' => ['index']]);  // indexはルートパラメータが必要ない。
        Route::resource('{blog}/articles', 'ArticleController',['only' => ['create','store']])->middleware('filterBy.routeParameters:blog');
        Route::resource('{blog}/articles', 'ArticleController',['only' => ['show','edit','update','destroy']])->middleware('filterBy.routeParameters:article');

        Route::prefix('{blog}/articles')->name('articles.')->group(function(){
            //コメント管理
            Route::resource('{article}/comments', 'CommentController',['only' => ['store', 'index']])->middleware('filterBy.routeParameters:article');
            Route::resource('{article}/comments', 'CommentController',['only' => ['destroy']])->middleware('filterBy.routeParameters:comment');

            //お気に入り管理
            Route::resource('{article}/favorites', 'FavoriteController',['only' => ['store']])->middleware('filterBy.routeParameters:article');
            Route::resource('{article}/favorites', 'FavoriteController',['only' => ['update']])->middleware('filterBy.routeParameters:favorite');
        });
    });
});


Route::namespace('User')->prefix('users')->name('users.')->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');

    // ログイン認証関連
    Auth::routes();

    // アカウント作成確認画面
    Route::get('register/confirm','Auth\RegisterController@confirm')->name('register.confirm');
    Route::post('register/confirm','Auth\RegisterController@post');

    // プロフィール設定
    Route::get('edit', 'ProfilesController@index')->name('profile.edit');
    Route::post('edit/update', 'ProfilesController@update')->name('profile.update');

    // プロフィール詳細
    Route::get('/{user}' , 'ProfilesController@show')->name('show');

    // twitterログイン
    Route::get('login/twitter', 'Auth\LoginController@redirectToTwitterProvider')->name('login.twitter');

    // facebookログイン
    Route::get('/login/facebook', 'Auth\LoginController@redirectToFacebookProvider')->name('login.facebook');

    // SNSログイン（コールバックのみ）
    Route::get('/login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->where('provider', 'twitter|facebook');
});


// 管理者機能
Route::namespace('Admin')->prefix('admins')->name('admins.')->group(function(){

    // ログイン認証関連;
    Auth::routes([
        'register' => false,
        'reset' => false
    ]);

    // アカウント作成確認画面
    Route::get('register/confirm','Auth\RegisterController@confirm')->name('register.confirm');

    Route::group(['middleware' => ['auth:admin']], function(){

        Route::get('/home', 'UserController@index')->name('home');

        Route::prefix('users')->name('users.')->group(function(){

            Route::get('', 'UserController@index')->name('index');
            Route::get('{user}', 'UserController@show')->name('show');
            Route::put('{user}', 'UserController@update')->name('update');
            Route::post('sendmail\{user}', 'UserController@sendmail')->name('sendmail');
        });

        Route::prefix('articles')->name('articles.')->group(function(){

            Route::get('', 'ArticleController@index')->name('index');
            Route::delete('{article}', 'ArticleController@destroy')->name('destroy');
        });

        Route::prefix('comments')->name('comments.')->group(function(){

            Route::get('', 'CommentController@index')->name('index');
            Route::put('{comment}', 'CommentController@update')->name('update');
            Route::delete('', 'CommentController@delete')->name('delete');
        });
    });
});
