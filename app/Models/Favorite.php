<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Interfaces\AssurableRouteParameters;
use App\Models\Traits\AssurableRouteParametersTrait;

class Favorite extends Model
{
    use AssurableRouteParametersTrait;

    protected $fillable = ['article_id','user_id','status'];

    //
    public function article(){
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * パラメータに応じて、Favoriteインスタンス存在チェック
     * @param array params = ['user' => xx , 'blog' => xx, 'article' => xx, 'favorite' => xx]
     * @return bool
     */
    /* public static function isExist($params){
        if(isset($params['favorite']) && isset($params['article'])){
            $favorite = self::find($params['favorite']);

            if($favorite && $favorite->article_id == $params['article'])
                return Article::isExist($params);
        }
        return false;
    }*/

    /**
     * 該当エッセイに対して、ユーザがお気に入り登録をしているかどうか確認
     *
     * @param article_id $article_id
     * @return mixed
     * ※登録済みの場合はFavoriteインスタンスを、していない場合はnullを返す。
     */
    public static function firstOrNull($user_id, $article_id){
        $favorite = Favorite::where('user_id', $user_id)
                            ->where('article_id', $article_id)
                            ->first();

        return $favorite;
    }

    /**
     * 該当エッセイの、ステータスがtrueなお気に入りを取得
     *
     * @param article_id $article_id
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getValidFavorites($article_id){
        $valid_favorites = Favorite::where('article_id', $article_id)
                            ->where('status', true)
                            ->get();

        return $valid_favorites;
    }

    /**
     * 該当エッセイの、ステータスがtrueなお気に入りのビルダーを取得
     *
     * @param article_id $article_id
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getValidFavoritesBuilder($article_id){
        $valid_favorites_builder = Favorite::where('article_id', $article_id)
                            ->where('status', true);

        return $valid_favorites_builder;
    }
}
