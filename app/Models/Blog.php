<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Blog extends Model
{
    protected $fillable = ['user_id','title','status_id'];

    //
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function status(){
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function articles(){
        return $this->hasMany(Article::class, 'blog_id', 'id');
    }

    /**
     * ブログインスタンスと認証済みユーザが対応しているかどうか
     * @param App\Models\Blog
     * @return bool $isAuth
     */
    /*public function isAuth($blog){
        return Auth::id() === $blog->user_id
    }*/

    /**
     * ブログが非公開かどうか
     * @return bool
     */
    public function isPrivate(){
        if(Auth::id() === $this->user_id)
            return false;
        return Status::isPrivate($this->status);
    }

    /**
     * ブログ所有ユーザ以外ならリダイレクト
     * @return \Illuminate\Http\Response
     */
    public function redirectShow(){
        return redirect(route('users.blogs.show', ['user' => $this->user->id, 'blog' => $this->id]));
    }

    /**
     * パラメータに応じて、Blogインスタンス存在チェック
     * @param array params = ['user' => xx , 'blog' => xx]
     * @return bool
     */
    public static function isExist($params){
        if(isset($params['blog']) && isset($params['user'])){
            $blog = self::find($params['blog']);

            if($blog && $blog->user_id == $params['user'])
                return User::isExist($params);
        }
        return false;
    }
}