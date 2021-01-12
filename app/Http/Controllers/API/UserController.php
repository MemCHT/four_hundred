<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    /**
     * ユーザーをidから取得するapi
     *
     * @return \Illuminate\Http\Response
     */
    public function get($user){
        // apiには例外処理つけたいかも
        $user = User::find($user);

        return $user;
    }
}
