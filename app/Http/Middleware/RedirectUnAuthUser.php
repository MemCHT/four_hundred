<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\User;
use App\Models\Blog;
use App\Models\Article;

use Illuminate\Support\Facades\Auth;

class RedirectUnAuthUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $layer='blog')
    {
        $user_id = $request->route()->parameters()['user'];

        // 不正ユーザアクセス時にリダイレクト
        if(Auth::id() !== intval($user_id)){
            return response($this->getResponseByLayer($layer, $request));
        }

        return $next($request);
    }

    /**
     * レイヤーによってリダイレクト先を変える
     * 
     * @param string $route_layer
     * @return Model
     */
    public function getResponseByLayer($layer, $request){
        $params = $request->route()->parameters();

        $response = (object)[];

        switch($layer){
            case'blog': $response = redirect()->route('users.blogs.show', ['user' => $params['user'], 'blog' => $params['blog']]); break;
            case'article': $response = redirect()->route('users.blogs.articles.show', ['user' => $params['user'], 'blog' => $params['blog'], 'article' => $params['article']]); break;
            default: $response = view('others.not_exist') ; break;
        }
        
        return $response;
    }
}
