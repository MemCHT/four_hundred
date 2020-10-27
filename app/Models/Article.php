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

    /**
     * articleインスタンスにある有効なお気に入りを取得
     * @return Illuminate\Database\Eloquent\Collection (Favorite)
     */
    public function validFavorites(){
        return Favorite::getValidFavorites($this->id);
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
     * Article一覧表示用インスタンス取得
     * @param  Illuminate\Http\Request
     * @return  Illuminate\Pagination\LengthAwarePaginator (article)
     */
    public static function searchArticlesByKeyword($request){
        $articles = Article::select('*');

        $keyword = $request->input('keyword');
        $status_id = $request->input('status_id');

        $session_has_keyword = $request->session()->has('keyword');
        $session_has_status_id = $request->session()->has('status_id');
        $request_has_page = $request->has('page');

        // 1. 検索もページ移動もしていないとき、keywordセッションを破棄する。（ヘッダから直接飛んだ時）
        if(isset($keyword) === false && $request_has_page === false){
            $request->session()->forget('keyword');
        }

        // 1. 絞り込みもページ移動もしていないとき、status_idセッションを破棄する。
        if( (isset($status_id) === false || $status_id === "all") && $request_has_page === false){
            $request->session()->forget('status_id');
        }

        // 2. 検索後にページボタン押下時、セッションからkeywordを取得
        if( $session_has_keyword && $request_has_page)
            $keyword = $request->session()->get('keyword');
        
        // 2. 絞り込み後にページボタン押下時、セッションからkeywordを取得
        if( $session_has_status_id && $request_has_page)
            $status_id = $request->session()->get('status_id');
        
        // 3. キーワード（name, email）によって検索処理
        if(isset($keyword)){
            $articles = $articles->where('title', 'like', '%'.$keyword.'%')->orWhere('body', 'like', '%'.$keyword.'%');
            $request->session()->put('keyword', $keyword);
        }

        // 4. all以外のステータス(status_id)によって絞り込み
        if(isset($status_id) && $status_id !== "all"){
            $articles = $articles->where('status_id', $status_id);
            $request->session()->put('status_id', $status_id);
        }

        $articles = $articles->orderBy('updated_at', 'DESC')->paginate(10);

        return $articles;
    }
}
