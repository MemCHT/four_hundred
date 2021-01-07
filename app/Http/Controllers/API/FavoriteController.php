<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * 記事に紐づくお気に入り（複数）を取得
     */
    public function list(Article $article){
        return $article->validFavorites();
    }

    /**
     * 記事に紐づくお気に入りのうち、ログイン済みユーザーに該当するお気に入りが存在するか判断
     *
     * うまくお気に入り登録が確認できない → 指定するuser_idにBlogのuserを利用していたため、ログイン済みユーザのものを使うよう修正した。
     * ※この時Kernelのapiの欄に、Authが使えるようになるミドルウェアなどを追加した。
     */
    public function existUserOnArticle(Article $article){
        $user_id = Auth::guard('user')->id();
        return $article->existsUserAtFavorites($user_id).'';
    }
}
