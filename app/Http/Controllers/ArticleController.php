<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleFormRequest;
use App\Http\Requests\BlogFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Blog;
use App\Models\Status;
use App\Models\Article;

class ArticleController extends Controller
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
        $statuses = Status::all();
        $user = Auth::user();

        return view('articles.create',compact('statuses', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ArticleFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleFormRequest $request)
    {
        $inputs = $request->all();
        
        $user = Auth::user();
        $blog = $user->blog;    //blogの取得方法を改善できる
        $inputs['blog_id'] = $blog->id;
        
        $article = Article::create($inputs);

        return redirect(route('users.blogs.articles.show', ['user' => $user->id, 'blog' => $blog->id, 'article' => $article->id]))->with('success','エッセイの投稿を完了しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $user_id
     * @param  int  $blog_id
     * @param  int  $article_id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id,$blog_id,$article_id)     //URLに対応させるための排他処理が必要
    {
        $article = Article::find($article_id);
        $user = Auth::user();

        return view('articles.show',compact('article','user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $user_id
     * @param  int  $blog_id
     * @param  int  $article_id
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id,$blog_id,$article_id)     //URLに対応させるための排他処理が必要（どのレイヤーでバリデーションをつけるか）
    {
        $user = Auth::user();
        $article = Article::find($article_id);
        $statuses = Status::all();

        return view('articles.edit',compact('user','article','statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ArticleFormRequest  $request
     * @param  int  $user_id
     * @param  int  $blog_id
     * @param  int  $article_id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleFormRequest $request, $user_id,$blog_id,$article_id)
    {
        $inputs = $request->all();

        $user = Auth::user();
        $blog = $user->blog;    //blogの取得方法を改善できる
        $article = Article::find($article_id);

        $article->update($inputs);

        return redirect(route('users.blogs.articles.show', ['user' => $user->id, 'blog' => $blog->id, 'article' => $article->id]))->with('success','エッセイの編集を完了しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $user_id
     * @param  int  $blog_id
     * @param  int  $article_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id,$blog_id,$article_id)
    {
        $user = Auth::user();
        $blog = $user->blog;
        $article = Article::find($article_id);
        Article::destroy($article_id);

        return redirect(route('users.blogs.show', ['user' => $user->id, 'blog' => $blog->id]))->with('success','エッセイ「'.$article->title.'」を削除しました');
    }
}
