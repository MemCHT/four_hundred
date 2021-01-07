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
     * パラメータに応じて、Articleインスタンス存在チェック
     * @param array params = ['user' => xx , 'blog' => xx, 'article' => xx]
     * @return bool
     */
    /*public static function isExist($params){
        if(isset($params['article']) && isset($params['blog'])){
            $article = self::find($params['article']);

            if($article && $article->blog_id == $params['blog'])
                return Blog::isExist($params);
        }
        return false;
    }*/

    /**
     * articleインスタンスにある有効なお気に入りを取得
     * @return Illuminate\Database\Eloquent\Collection (Favorite)
     */
    public function validFavorites(){
        return Favorite::getValidFavorites($this->id);
    }

    /**
     * articleインスタンスにある有効なお気に入りの中に、特定のuserのものが含まれているかどうか判定
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
        if(is_null($builder))
            $builder = Article::select('*');

        $builder->join(
                    \DB::raw('(SELECT articles.id AS popularity_articles_id, count(favorites.id) AS sum_favorites FROM articles
                        LEFT JOIN favorites ON favorites.article_id = articles.id
                        GROUP BY articles.id) AS popularity'),
                    'articles.id', '=', 'popularity.popularity_articles_id'
        )
        ->orderBy('popularity.sum_favorites', 'DESC');

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
     * Article一覧表示用インスタンス取得 + Article検索処理
     * @param  Illuminate\Http\Request
     * @return  Illuminate\Pagination\LengthAwarePaginator (article)
     */
    /*public static function searchArticlesByKeyword($request){
        // DB::enableQueryLog();

        $articles = Article::select('*');

        $keyword = $request->input('keyword');

        $session_has_keyword = $request->session()->has('keyword');
        $request_has_page = $request->has('page');

        // 1. 検索もページ移動もしていないとき、keywordセッションを破棄する。（ヘッダから直接飛んだ時）
        if(isset($keyword) === false && $request_has_page === false)
            $request->session()->forget('keyword');

        // 2. 検索後にページボタン押下時、セッションからkeywordを取得
        if( $session_has_keyword && $request_has_page)
            $keyword = $request->session()->get('keyword');

        /**
         * ? 以下3,4で同時検索されない
         *   -- 出力されているクエリを参照することで解決
         * DB::enableQueryLog();
         * ~
         * dd(DB::getQueryLog();)
         * で参照可能
         */

        // 3. キーワード（name, email）によって検索処理
        /*if(isset($keyword)){
            // これだと (where 'title'=hoge) + (where 'body'=hoge) ∴A+BC
            // $articles->where('title', 'like', '%'.$keyword.'%')->orWhere('body', 'like', '%'.$keyword.'%');

            // これで ((where 'title=hoge) + (where 'body'=hoge)) ∴(A+B)C
            $articles->where(function($articles) use($keyword){
                $articles->where('title', 'like', '%'.$keyword.'%')
                         ->orWhere('body', 'like', '%'.$keyword.'%');
            });

            $request->session()->put('keyword', $keyword);
        }

        $articles = self::narrowArticlesFromStatus($request, $articles);

        $articles = $articles->orderBy('updated_at', 'DESC')->paginate(8);

        // dd(DB::getQueryLog());

        return $articles;
    }*/

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
     * @param  array  ['title' => 'hoge', 'body' => 'hoge', 'blogTitle' => 'hoge]
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
     * ArticleをStatusで絞り込み
     *
     * @param Illuminate\Database\Eloquent\Builder $articles
     * @return Illuminate\Database\Eloquent\Builder
     */
    /*private static function narrowArticlesFromStatus($request, $articles){

        $status_id = $request->input('status_id');

        $session_has_status_id = $request->session()->has('status_id');
        $request_has_page = $request->has('page');

        // 1. 絞り込みもページ移動もしていないとき、status_idセッションを破棄する。
        if( (isset($status_id) === false || $status_id === "all") && $request_has_page === false)
            $request->session()->forget('status_id');

        // 2. 絞り込み後にページボタン押下時、セッションからstatus_idを取得
        if( $session_has_status_id && $request_has_page)
            $status_id = $request->session()->get('status_id');

        // 3. all以外のステータス(status_id)によって絞り込み
        if(isset($status_id) && $status_id !== "all"){
            $articles->where('status_id', $status_id);
            $request->session()->put('status_id', $status_id);
        }

        return $articles;
    }*/

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
