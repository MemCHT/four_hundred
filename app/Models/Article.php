<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Interfaces\AssurableRouteParameters;
use App\Models\Traits\AssurableRouteParametersTrait;

use App\Models\Blog;
use App\Models\Favorite;
use App\Models\Comment;

class Article extends Model
{
    use AssurableRouteParametersTrait;

    protected $fillable = ['blog_id','title','body','status_id','published_at'];
    protected $dates = ['published_at'];

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
     * articleインスタンスにある有効なお気に入りを取得
     * @return Illuminate\Database\Eloquent\Collection (Favorite)
     */
    public function validFavorites(){
        return Favorite::getValidFavorites($this->id);
    }

    /**
     * articleインスタンスにある有効なお気に入りの中に、特定（引数）のuserのものが含まれているかどうか判定
     *
     * @param int $user_id
     * @return boolean
     */
    public function existsUserAtFavorites($user_id){
        return Favorite::getValidFavoritesBuilder($this->id)->where('user_id', $user_id)->exists();
    }

    /**
     * commentインスタンスにある有効なお気に入りを取得
     * @return Illuminate\Database\Eloquent\Collection (Comment)
     */
    public function validComments(){
        return Comment::getValidComments($this->id);
    }

    /**
     * 公開済みのarticleを全件取得
     * @return Illuminate\Database\Eloquent\Collection (Article)
     */
    public static function getPublicArticles(){
        $articles = Article::where('status_id', Status::getByName('公開')->id)->get();
        return $articles;
    }

    /**
     * 新着順に一覧をビルド
     * @param Illuminate\Database\Eloquent\Builder $builder = Article::hoge()
     * @return Illuminate\Database\Eloquent\Builder
     */
    public static function sortNewest($builder=null){
        if(is_null($builder))
            $builder = Article::select('*');

        $builder->orderBy('updated_at', 'DESC');

        return $builder;
    }

    /**
     * 人気順に一覧をビルド
     * @param Illuminate\Database\Eloquent\Builder $builder = Article::hoge()
     * @return Illuminate\Database\Eloquent\Builder
     */
    public static function sortPopularity($builder=null){
        \DB::enableQueryLog();
        if(is_null($builder))
            $builder = Article::select('*');

        $builder->where('articles.status_id', Status::getByName('公開')->id)
                ->join(
                    \DB::raw('(SELECT articles.id AS popularity_articles_id, count(favorites.id) AS sum_favorites FROM articles
                        LEFT JOIN (SELECT * FROM favorites WHERE favorites.status = true) AS favorites ON favorites.article_id = articles.id
                        GROUP BY articles.id) AS popularity'),
                    'articles.id', '=', 'popularity.popularity_articles_id'
        )->orderBy('popularity.sum_favorites', 'DESC');
        //dd(\DB::getQueryLog());

        return $builder;
    }

    /**
     * 公開済み一覧をビルド
     * @param Illuminate\Database\Eloquent\Builder $builder = Article::hoge()
     * @return Illuminate\Database\Eloquent\Builder
     */
    public static function buildToPublic($builder=null){
        if(is_null($builder))
            $builder = Article::select('*');

        $builder->where('status_id', Status::getByName("公開")->id);

        return $builder;
    }

    /**
     * title検索
     * @param  Illuminate\Database\Eloquent\Builder
     * @return  Illuminate\Database\Eloquent\Builder
     */
    private static function searchTitle($builder, $title){
        $builder->where('title', 'like',  "%$title%");

        return $builder;
    }
    /**
     * blogTitle検索
     * @param  Illuminate\Database\Eloquent\Builder
     * @return  Illuminate\Database\Eloquent\Builder
     */
    private static function searchBlogTitle($builder, $blog_title){
        $blog_ids = Blog::where('title', 'like', "%$blog_title%")->get(['id']);

        // whereInはパラメータがEloquent/Collectionでも良い...!すげ
        $builder->whereIn('blog_id', $blog_ids);

        return $builder;
    }

    /**
     * body検索
     * @param  Illuminate\Database\Eloquent\Builder
     * @return  Illuminate\Database\Eloquent\Builder
     */
    private static function searchBody($builder, $body){
        $builder->where('body', 'like',  "%$body%");

        return $builder;
    }

    /**
     * userName検索
     * // UserのsearchNameからとってきたロジックの方が良いかも。
     *
     * @param Illuminate\Database\Eloquent\Builder
     * @return Illuminate\Database\Eloquent\Builder
     */
    private static function searchUserName($builder, $user_name){
        $user_ids = User::where('name', 'like', "%$user_name%")->get('id');

        $blog_ids = Blog::whereIn('user_id', $user_ids)->get('id');
        $articles = $builder->whereIn('blog_id', $blog_ids);

        return $articles;
    }

    /**
     * 連想配列（キーと値）で検索する
     * @param  array  ['title' => 'hoge', 'body' => 'hoge', 'blogTitle' => 'hoge', 'userName' => 'hoge']
     * @return  Illuminate\Database\Eloquent\Builder
     */
    private static $search_keys = ['title','body','blogTitle', 'userName'];
    public static function search( $inputs ){
        $articles = self::select('*');

        foreach($inputs as $key => $value){
            $method = 'search'.ucfirst($key);

            if(in_array($key, self::$search_keys))
                $articles = self::$method($articles, $value);
        }

        return $articles;
    }

    /**
     * 該当ユーザがArticleインスタンスをいいねしているか確認
     *
     * @param int $user_id
     * @return App\Models\Favorite | null
     */
    public function getFavorite($user_id){
        $favorite = Favorite::where('article_id', $this->id)->where('user_id', $user_id);

        return $favorite->count() > 0 ? $favorite->first() : null;
    }

    /**
     * 該当記事の有効favorite数を取得
     *
     * @return int
     */
    public function getFavoritesCount(){
        $count = $this->favorites()->where('status', true)->count();

        return $count;
    }

    /**
     * 投稿主の一つ次の記事を取得
     *
     * @return App\Models\Article
     */
    public function getNext(){
        $next = Article::where('blog_id', $this->blog_id)
                        ->where('status_id', Status::getByName('公開')->id)
                        ->where('updated_at', '>', $this->updated_at)
                        ->orderBy('updated_at', 'ASC')
                        ->first();

        return $next;
    }

    /**
     * 投稿主の一つ前の記事を取得
     *
     * @return App\Models\Article
     */
    public function getPrev(){
        $prev = Article::where('blog_id', $this->blog_id)
                        ->where('status_id', Status::getByName('公開')->id)
                        ->where('updated_at', '<', $this->updated_at)
                        ->orderBy('updated_at', 'DESC')
                        ->first();

        return $prev;
    }
}
