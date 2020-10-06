<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\User;
use App\Models\Blog;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Favorite;

class FilterByRouteParameters
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $route_layer='user')
    {
        // 全てのルートパラメータを取得
        $allRouteParams = $request->route()->parameters();

        // middleware呼び出し時の引数によって処理のレイヤーを設定
        $model = $this->selectLayer($route_layer);

        // 設定したレイヤーに対応したパラメータ確認処理を行う
        // パラメータに異常がある場合、not_existビューを表示
        if($model::isExist($allRouteParams) === false)
            return response(view('others.not_exist'));

        return $next($request);
    }

    /**
     * アクセスするレイヤーによって処理させるモデルを選ぶ
     * 
     * @param string $route_layer
     * @return Model
     */
    public function selectLayer($route_layer){
        $model = new User();

        switch($route_layer){
            case'blog': $model = new Blog(); break;
            case'article': $model = new Article(); break;
            case'comment': $model = new Comment(); break;
            case'favorite': $model = new Favorite(); break;
            default: break;
        }
        
        return $model;
    }
}
