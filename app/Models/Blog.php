<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use App\Models\Interfaces\AssurableRouteParameters;
use App\Models\Traits\AssurableRouteParametersTrait;

class Blog extends Model
{
    use AssurableRouteParametersTrait;

    protected $fillable = ['user_id','title','status_id','overview'];

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
    public function authUser(){

        if(Auth::id() !== intval($this->user_id)){
            return response(redirect()->route('users.blogs.show', ['user' => $this->user_id, 'blog' => $this->id]));
        }
    }

    /**
     * パラメータに応じて、Blogインスタンス存在チェック
     * @param array params = ['user' => xx , 'blog' => xx]
     * @return bool
     */
    /* public static function isExist($params){
        if(isset($params['blog']) && isset($params['user'])){
            $blog = self::find($params['blog']);

            if($blog && $blog->user_id == $params['user'])
                return User::isExist($params);
        }
        return false;
    }*/

    /**
     * 全記事のお気に入り総数を取得
     * @return int
     */
    public function getFavoritesCount(){
        $favorites_count = self::where('blogs.id', $this->id)
                                ->join('articles', 'blogs.id', 'articles.blog_id')
                                ->join('favorites', 'articles.id', 'favorites.article_id')
                                ->where('favorites.status', true)
                                ->count();

        return $favorites_count;
    }

    /**
     * 公開済み記事総数を取得
     * @return int
     */
    public function getArticlesCount(){
        $articles_count = $this->articles()->where('articles.status_id', Status::getByName('公開')->id)
                                         ->count();

        return $articles_count;
    }

    /**
     * ブログの最新記事を取得
     *
     * @return App\Models\Article
     */
    public function getLatestArticle(){
        $latest_article = Article::where('blog_id', $this->id)->where('status_id', Status::getByName('公開')->id)->orderBy('updated_at', 'DESC')->first();

        return $latest_article;
    }

    /**
     * ブログインスタンスをフォーマット（お気に入り・記事総数を追加）する
     * ※破壊的メソッド
     * @return App\Models\Blog
     */
    public function formatForIndex(){
        $status_id_public = Status::getByName('公開')->id;

        //記事全てのfavorite総数を取得
        $favorites_count = $this->getFavoritesCount();
        $this->favorites_count = $favorites_count;

        //記事総数を取得
        $this->articles_count = $this->getArticlesCount();

        //ブログの最新記事を取得
        $this->latest_article = $this->getLatestArticle();

        return $this;
    }

    /**
     * ブログ一覧表示用オブジェクトを取得
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    /*public static function getIndexObject(){
        $status_id_public = Status::getByName('公開')->id;

        $blogs = self::where('status_id', $status_id_public)
                     ->orderBy('updated_at', 'DESC')
                     ->paginate(4);

        foreach($blogs as $blog){
            $blog->formatForIndex();
        }

        return $blogs;
    }*/
    public static function buildPublic(){
        $status_id_public = Status::getByName('公開')->id;

        $blogs = self::where('status_id', $status_id_public)
                     ->orderBy('updated_at', 'DESC');

        return $blogs;
    }

    /**
     * 閲覧用Articleを指定数getする
     *
     * @param int $limit
     * @param Illuminate\Database\Eloquent\Collection ($articles)
     */
    public function getArticles($limit){
        $articles = Article::where('blog_id', $this->id)
                            ->where('status_id', Status::getByName('公開')->id)
                            ->orderBy('updated_at', 'DESC')
                            ->limit($limit)
                            ->get();

        return $articles;
    }

    /**
     * 閲覧用Articleを新着順にbuildする
     *
     * @param Illuminate\Database\Eloquent\Collection ($articles)
     */
    public function buildArticlesNewest(){
        $articles = Article::where('blog_id', $this->id)
                            ->where('status_id', Status::getByName('公開')->id)
                            ->orderBy('updated_at', 'DESC');
        return $articles;
    }

    /**
     * 閲覧用Articleを新着順にbuildする
     *
     * @param Illuminate\Database\Eloquent\Collection ($articles)
     */
    public function buildArticlesPopularity(){

        $articles = Article::where('articles.blog_id', $this->id)
                            ->where('articles.status_id', Status::getByName('公開')->id)
                            ->join(
                                \DB::raw('(SELECT articles.id AS popularity_articles_id, count(favorites.id) AS sum_favorites FROM articles
                                    LEFT JOIN favorites ON favorites.article_id = articles.id
                                    GROUP BY articles.id) AS popularity'),
                                'articles.id', '=', 'popularity.popularity_articles_id'
                            )
                            ->orderBy('popularity.sum_favorites', 'DESC');
        // dd($articles);
        return $articles;
    }
}
