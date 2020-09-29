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

Auth::routes();

// プロフィール設定
Route::get('/users/edit', 'ProfilesController@index')->name('profile.edit');
Route::post('/users/edit/update', 'ProfilesController@update')->name('profile.update');

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('users')->name('users.')->group(function(){

    Route::resource('{user}/blogs', 'BlogController');

    Route::prefix('{user}/blogs')->name('blogs.')->group(function(){
        Route::resource('{blog}/articles', 'ArticleController');

        Route::prefix('{blog}/articles')->name('articles.')->group(function(){
            Route::resource('{article}/comments', 'CommentController');
        });
    });
});