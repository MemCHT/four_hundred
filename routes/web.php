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

// ログイン機能
Route::get('users/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('users/login', 'Auth\LoginController@login');
Route::post('users/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('users/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('users/register', 'Auth\RegisterController@register');

Route::get('users/register/confirm','Auth\RegisterController@confirm')->name('register.confirm');
Route::post('users/register/confirm','Auth\RegisterController@post');

Route::get('users/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('users/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('users/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('users/password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');









Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('users')->name('users.')->group(function(){
    Route::resource('{user_id}/blogs', 'BlogController');
});