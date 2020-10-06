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
     * @param  int  $user_id
     * @param  int  $blog_id
     * @return \Illuminate\Http\Response
     */
    public function create($user_id,$blog_id)
    {
        $user = User::find($user_id);
        $blog = Blog::find($blog_id);
        $statuses = Status::all();

        //ブログ所有ユーザ以外ならリダイレクト
        if(Auth::id() !== intval($user_id)){
            return redirect(route('users.blogs.show', ['user' => $user_id, 'blog' => $blog_id]));
        }

        return view('articles.create',compact('statuses', 'user', 'blog'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ArticleFormRequest  $request
     * @param  int  $user_id
     * @param  int  $blog_id
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleFormRequest $request,$user_id,$blog_id)
    {
        $user = User::find($user_id);
        $blog = Blog::find($blog_id);

        //ブログ所有ユーザ以外ならリダイレクト
        if(Auth::id() !== intval($user_id)){
            return redirect(route('users.blogs.show', ['user' => $user_id, 'blog' => $blog_id]));
        }

        $inputs = $request->all();
        $inputs['blog_id'] = $blog->id;
        
        $article = Article::create($inputs);

        return redirect(route('users.blogs.articles.show', ['user' => $user_id, 'blog' => $blog_id, 'article' => $article->id]))->with('success','エッセイの投稿を完了しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $user_id
     * @param  int  $blog_id
     * @param  int  $article_id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id,$blog_id,$article_id)
    {
        $user = User::find($user_id);
        $article = Article::find($article_id);

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
    public function edit($user_id,$blog_id,$article_id)
    {
        $user = User::find($user_id);
        $article = Article::find($article_id);
        $statuses = Status::all();

        //記事所有ユーザ以外ならリダイレクト
        if(Auth::id() !== intval($user_id)){
            return redirect(route('users.blogs.articles.show', ['user' => $user_id, 'blog' => $blog_id, 'article' => $article_id]));
        }

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

        $user = User::find($user_id);
        $blog = Blog::find($blog_id);
        $article = Article::find($article_id);

        //記事所有ユーザ以外ならリダイレクト
        if(Auth::id() !== intval($user_id)){
            return redirect(route('users.blogs.articles.show', ['user' => $user_id, 'blog' => $blog_id, 'article' => $article_id]));
        }

        $article->update($inputs);

        return redirect(route('users.blogs.articles.show', ['user' => $user_id, 'blog' => $blog_id, 'article' => $article_id]))->with('success','エッセイの編集を完了しました');
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
        $user = User::find($user_id);
        $blog = Blog::find($blog_id);
        $article = Article::find($article_id);

        //記事所有ユーザ以外ならリダイレクト
        if(Auth::id() !== intval($user_id)){
            return redirect(route('users.blogs.articles.show', ['user' => $user_id, 'blog' => $blog_id, 'article' => $article_id]));
        }   

        Article::destroy($article_id);

        return redirect(route('users.blogs.show', ['user' => $user_id, 'blog' => $blog_id]))->with('success','エッセイ「'.$article->title.'」を削除しました');
    }
}
