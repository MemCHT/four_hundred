<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Interfaces\AssurableRouteParameters;
use App\Models\Traits\AssurableRouteParametersTrait;

class Comment extends Model
{
    use AssurableRouteParametersTrait;

    protected $fillable = ['article_id','user_id','body', 'status_id'];

    //
    public function article(){
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function status(){
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    /**
     * Articleに属する有効コメントをすべて取得する
     * @param int $article_id
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getValidComments($article_id){
        $comments = Comment::where('article_id', $article_id)->where('status_id', Status::getByName('公開')->id)->get();
        return $comments;
    }

    /**
     * userName検索
     * @param  Illuminate\Database\Eloquent\Builder
     * @return  Illuminate\Database\Eloquent\Builder
     */
    private static function searchUserName($builder, $user_name){
        $user_ids = User::where('name', 'like', "%$user_name%")->get(['id']);

        $builder->whereIn('user_id', $user_ids);

        return $builder;
    }

    /**
     * articleTitle検索
     * @param  Illuminate\Database\Eloquent\Builder
     * @return  Illuminate\Database\Eloquent\Builder
     */
    private static function searchArticleTitle($builder, $article_title){
        $article_ids = Article::where('title', 'like', "%$article_title%")->get(['id']);

        $builder->whereIn('article_id', $article_ids);

        return $builder;
    }

    /**
     * 連想配列（キーと値）で検索する
     * @param  array  ['userName' => 'hoge', 'articleTitle' => 'hoge']
     * @return  Illuminate\Database\Eloquent\Builder
     */
    private static $search_keys = ['userName', 'articleTitle'];
    public static function search( $inputs ){
        $comments = self::select('*');

        foreach($inputs as $key => $value){
            $method = 'search'.ucfirst($key);

            if(in_array($key, self::$search_keys))
                $comments = self::$method($comments, $value);
        }

        return $comments;
    }
}
