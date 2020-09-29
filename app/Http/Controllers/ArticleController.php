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
        $blog = $user->blog;
        $inputs['blog_id'] = $blog->id;
        
        $article = Article::create($inputs);

        return redirect(route('users.blogs.articles.show', ['user' => $user->id, 'blog' => $blog->id, 'article' => $article->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param int $user_id
     * @param int $blog_id
     * @param int $article_id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id,$blog_id,$article_id)
    {
        $article = Article::find($article_id);
        $user = Auth::user();

        return view('articles.show',compact('article','user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id,$blog_id,$article_id)
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
        $blog = $user->blog;
        //$inputs['blog_id'] = $blog->id;
        $article = Article::find($article_id);

        $article->update($inputs);

        return redirect(route('users.blogs.articles.show', ['user' => $user->id, 'blog' => $blog->id, 'article' => $article->id]));
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
