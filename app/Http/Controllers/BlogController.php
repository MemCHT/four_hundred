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

class BlogController extends Controller
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

        return view('blogs.create',compact('statuses', 'user'));
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
        
        Article::create($inputs);

        return redirect(route('users.blogs.show', ['user' => $user->id, 'blog' => $blog->id]));
    }

    /**
     * ブログを表示
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id,$blog_id)
    {
        $user = User::get($user_id);
        $blog = Blog::get($blog_id);
        $articles = $blog->articles()->paginate(10);

        //ブログが非公開 かつ ブログ所有ユーザでない なら別のビューを表示
        if(Status::isPrivate($blog->status) && Auth::id() !== $blog->user_id){
            return view('blogs.private');
        }

        return view('blogs.show',compact('user','blog','articles'));
    }

    /**
     * ブログの編集画面を表示
     *
     * @param  int  $user_id
     * @param int $blog_id
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id,$blog_id)
    {
        $user = Auth::user();
        $blog = Blog::get($blog_id);
        $articles = $blog->articles()->paginate(10);

        //ブログ所有ユーザ以外ならリダイレクト
        if(Auth::id() !== $blog->user_id){
            return redirect(route('users.blogs.show', ['user' => $user_id, 'blog' => $blog_id]));
        }

        return view('blogs.edit',compact('user','blog','articles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\BlogFormRequest  $request
     * @param  int  $user_id
     * @param int $blog_id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogFormRequest $request, $user_id, $blog_id)
    {
        $title = $request->input('title');

        Blog::get($blog_id)->update(['title' => $title]);

        return redirect(route('users.blogs.show', ['user' => $user_id, 'blog' => $blog_id]));
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
