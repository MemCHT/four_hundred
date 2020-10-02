<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Blog;
use App\Models\Article;
use App\Models\Comment;

class CommentController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $user_id
     * @param  int  $blog_id
     * @param  int  $article_id
     * @param  int  $comment_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id,$blog_id,$article_id,$comment_id)
    {
        $user = Auth::user();
        $blog = $user->blog;
        $article = Article::find($article_id);
        $comment = Comment::find($comment_id);
        $comment_user = $comment->user;
        Comment::destroy($comment_id);

        return redirect(route('users.blogs.articles.show', ['user' => $user->id, 'blog' => $blog->id, 'article' => $article->id]))->with('success','「'.$comment_user->name.'」さんのコメントを削除しました');
    }
}
