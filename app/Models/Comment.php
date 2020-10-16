<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['article_id','user_id','body'];

    //
    public function article(){
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * パラメータに応じて、Commentインスタンス存在チェック
     * @param array params = ['user' => xx , 'blog' => xx, 'article' => xx, 'comment' => xx]
     * @return bool
     */
    public static function isExist($params){
        if(isset($params['comment']) && isset($params['article'])){
            $comment = self::find($params['comment']);

            if($comment && $comment->article_id == $params['article'])
                return Article::isExist($params);
        }
        return false;
    }
}
