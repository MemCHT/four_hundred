<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     * idと結びついたArticleインスタンスを返します。
     * 
     * @return App\Models\Article[]
     */

    /**
     * Blogをidで取得
     * 
     * @param int $id
     * @return App\Models\User
     */
    public static function get($id){
        return Blog::find($id);
    }
}