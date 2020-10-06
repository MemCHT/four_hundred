<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['blog_id','title','body','status_id'];

    //
    public function blog(){
        return $this->belongsTo(Blog::class, 'blog_id', 'id');
    }

    public function status(){
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function comments(){
        return $this->hasMany(Comment::class, 'article_id', 'id');
    }

    public function favorites(){
        return $this->hasMany(Favorite::class, 'article_id', 'id');
    }

    /**
     * パラメータに応じて、Articleインスタンス存在チェック
     * @param array params = ['user' => xx , 'blog' => xx, 'article' => xx]
     * @return bool
     */
    public static function isExist($params){
        if(isset($params['article']) && isset($params['blog'])){
            $article = self::find($params['article']);

            if($article && $article->blog_id == $params['blog'])
                return Blog::isExist($params);
        }
        return false;
    }
}
