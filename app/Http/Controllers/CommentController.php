<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentFormRequest;
// use Illuminate\Support\Facades\Notification;//  → 複数のユーザーに対しても送れる

use App\Models\User;
use App\Models\Blog;
use App\Models\Article;
use App\Models\Comment;
use App\Notifications\CommentNotification;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('redirect.unAuthUser:article')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id, $blog_id, $article_id)
    {
        $user = User::find($user_id);
        // $blog = Blog::find($blog_id);
        $article = Article::find($article_id);

        $comments = $article->comments;

        return view('users.comments.index', compact('user', 'article', 'comments'));
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
     * @param  \App\Http\Requests\CommentFormRequest  $request
     * @param  int  $user_id
     * @param  int  $blog_id
     * @param  int  $article_id
     * @return \Illuminate\Http\Response
     */
    public function store(CommentFormRequest $request, $user_id, $blog_id, $article_id)
    {
        $user = User::find($user_id);
        $blog = Blog::find($blog_id);
        $article = Article::find($article_id);

        $route = route('users.blogs.articles.show', ['user' => $article->blog->user_id, 'blog' => $blog_id, 'article' => $article_id]);
        $redirect = redirect($route);

        $input = $request->input();

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'article_id' => $article_id,
            'body' => $input['body']
        ]);

        $data = (object)[];
        $data->user = $user;
        $data->blog = $blog;
        $data->article = $article;
        $data->comment = $comment;
        $data->url = $route;

        // Twitterは利用規約等が未認証のため、email取得できない ∴メール送信されない
        $user->notify(new CommentNotification($data));

        return $redirect->with('success', 'コメントを投稿しました');
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @param  int  $blog_id
     * @param  int  $article_id
     * @param  int  $comment_id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $user_id,$blog_id,$article_id,$comment_id)
    {
        $user = User::find($user_id);
        $blog = Blog::find($blog_id);
        $article = Article::find($article_id);
        $comment = Comment::find($comment_id);
        $inputs = $request->all();

        $selected_comments = array_filter(array_map(function($key, $value){
            if(preg_match('/comment_/', $key))
                return $value;
            // dd($key);
        },array_keys($inputs), array_values($inputs))
        ,function($value){ return $value; });


        // dd($selected_comments);

        //記事所有ユーザ以外ならリダイレクト
        if(Auth::id() !== intval($user_id) && Auth::guard('admin')->check() === false){
            return redirect(route('users.blogs.articles.show', ['user' => $user_id, 'blog' => $blog_id, 'article' => $article_id]));
        }

        // $comment_user = $comment->user;
        // Comment::destroy($comment_id);

        Comment::whereIn('id', $selected_comments)->delete();

        return redirect(route('users.blogs.articles.comments.index', ['user' => $user_id, 'blog' => $blog_id, 'article' => $article_id]))->with('success','コメントを削除しました');
    }
}
