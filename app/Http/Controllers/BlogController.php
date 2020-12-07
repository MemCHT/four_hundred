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
    public function __construct()
    {
        $this->middleware('redirect.unAuthUser:blog')->only(['edit','update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $user_id)
    {
        $user = User::find($user_id);

        $input = $request->input() ? $request->input() : session('input');

        $blogs = Blog::search( $input ?? [] );

        $blogs = Blog::buildToPublic( $blogs );
        $blogs = $blogs->orderBy('updated_at', 'DESC');
        $blogs = $blogs->paginate(4);

        return view('blogs.index', compact('blogs', 'input'));
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

        $articles = $blog->articles()->orderBy('updated_at', 'DESC')->paginate(10);

        // 所有者がリンクに飛んだ場合、管理者ビューを表示
        if(Auth::guard('user')->id() === $blog->user_id)
            return view('users.blogs.show', compact('user', 'blog', 'articles'));

        // ブログが非公開 && ブログ所有ユーザでない なら別のビューを表示
        if($blog->isPrivate())
            return view('blogs.private');

        if(Auth::guard('user')->id() !== $blog->user_id)
            $articles = $blog->articles()->where('status_id', Status::getByName('公開')->id)->paginate(10);

        return view('blogs.show',compact('user','blog','articles'));
    }

    /**
     * ブログの編集画面を表示
     *
     * @param  int  $user_id
     * @param  int  $blog_id
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id,$blog_id)
    {
        $user = User::find($user_id);
        $blog = Blog::find($blog_id);
        $statuses = Status::all();

        //ブログ所有ユーザ以外ならリダイレクト
        //$blog->authUser();

        $articles = $blog->articles()->paginate(10);

        return view('users.blogs.edit',compact('user','blog','articles', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\BlogFormRequest  $request
     * @param  int  $user_id
     * @param  int  $blog_id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogFormRequest $request, $user_id, $blog_id)
    {
        $input = $request->input();
        $blog = Blog::find($blog_id);

        //ブログ所有ユーザ以外ならリダイレクト
        /*if(Auth::id() !== intval($user_id)){
            return redirect()->route('users.blogs.show', ['user' => $user_id, 'blog' => $blog_id]);
        }*/
        // dd($input);

        $blog->update($input);

        return redirect()->route('users.blogs.edit', ['user' => $user_id, 'blog' => $blog_id])
                         ->with('success','ブログタイトルの編集を完了しました');
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
