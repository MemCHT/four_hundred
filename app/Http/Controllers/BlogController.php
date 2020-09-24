<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Blog;
use App\Models\Status;

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $user_id
     * @param int $blog_id
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id,$blog_id)
    {
        $user = User::get($user_id);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @param int $blog_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id, $blog_id)
    {
        $blog_title = $request->input('blog_title');

        Blog::get($blog_id)->update(['title' => $blog_title]);

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
