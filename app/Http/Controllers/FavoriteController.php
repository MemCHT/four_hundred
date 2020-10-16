<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Blog;
use App\Models\Article;
use App\Models\Favorite;
use App\Notifications\FavoriteNotification;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @param  int  $blog_id
     * @param  int  $article_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $user_id, $blog_id, $article_id)
    {
        $user = User::find($user_id);
        $blog = Blog::find($blog_id);
        $article = Article::find($article_id);
        
        $route = route('users.blogs.articles.show', ['user' => $user_id, 'blog' => $blog_id, 'article' => $article_id]);

        $favorite = Favorite::create([
            'user_id' => Auth::id(),
            'article_id' => $article_id,
            'status' => true
        ]);

        $data = (object)[];
        $data->user = $user;
        $data->blog = $blog;
        $data->article = $article;
        $data->favorite = $favorite;
        $data->url = $route;

        $user->notify(new FavoriteNotification($data));

        return redirect($route)->with('success', '「'.$article->title.'」をお気に入り登録しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @param  int  $blog_id
     * @param  int  $article_id
     * @param  int  $favorite_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id, $blog_id, $article_id, $favorite_id)
    {
        // $user = User::find($user_id);
        // $blog = Blog::find($blog_id);
        $article = Article::find($article_id);
        $favorite = Favorite::find($favorite_id);
        $favorite->update(['status' => !($favorite->status)]);  // statusを反転させる。

        return redirect()->route('users.blogs.articles.show', ['user' => $user_id, 'blog' => $blog_id, 'article' => $article_id])
                         ->with('success', '「'.$article->title.'」のお気に入り登録を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
