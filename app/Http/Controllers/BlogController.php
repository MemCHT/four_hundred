<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleFormRequest;
use App\Http\Requests\BlogFormRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Router;

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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ArticleFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleFormRequest $request)
    {
        
    }

    /**
     * ブログを表示
     *
     * @param  int  $user_id
     * @param  int  $blog_id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id,$blog_id)
    {
        $user = User::find($user_id);
        $blog = Blog::find($blog_id);

        // ブログが非公開 && ブログ所有ユーザでない なら別のビューを表示
        if($blog->isPrivate()){
            return view('blogs.private');
        }

        $articles = $blog->articles()->paginate(10);

        return view('blogs.show',compact('user','blog','articles'));
    }

    /**
     * ブログの編集画面を表示
     *
     * @param  int  $user_id
     * @param  int  $blog_id
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id,$blog_id)     //URLに対応させるための排他処理が必要
    {
        $user = User::find($user_id);
        $blog = Blog::find($blog_id);

        //ブログ所有ユーザ以外ならリダイレクト
        if(Auth::id() !== intval($user_id)){
            return redirect(route('users.blogs.show', ['user' => $user_id, 'blog' => $blog_id]));
        }

        $articles = $blog->articles()->paginate(10);

        return view('blogs.edit',compact('user','blog','articles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\BlogFormRequest  $request
     * @param  int  $user_id
     * @param  int  $blog_id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogFormRequest $request, $user_id, $blog_id)        //URLに対応させるための排他処理が必要
    {
        $title = $request->input('title');
        $blog = Blog::find($blog_id);
        
        //ブログ所有ユーザ以外ならリダイレクト
        if(Auth::id() !== intval($user_id)){
            return redirect(route('users.blogs.show', ['user' => $user_id, 'blog' => $blog_id]));
        }
        
        $blog->update(['title' => $title]);

        return redirect(route('users.blogs.show', ['user' => $user_id, 'blog' => $blog_id]))->with('success','ブログタイトルの編集を完了しました');
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
