<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleFormRequest;
use App\Http\Requests\BlogFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use App\Models\User;
use App\Models\Blog;
use App\Models\Status;
use App\Models\Article;
use App\Models\Favorite;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('redirect.unAuthUser:blog')->only(['create','store']);
        $this->middleware('redirect.unAuthUser:article')->only(['edit','update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Illuminate\Http\Request;
     * @param  int  $user_id
     * @param  int  $blog_id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $user_id, $blog_id)
    {

        $input = $request->input() ? $request->input() : session('input');
        $type = $request->input('type') ? $request->input('type') : session('input')['type'];
        // dd($type);
        $method = 'sort'.ucfirst($type);

        // dd(session('input'));

        $articles = Article::search( $input ?? [] );
        // dd($articles->paginate(8));
        $articles = $type ? Article::$method($articles) : Article::sortNewest($articles);
        $articles = Article::buildToPublic($articles);
        $articles = $articles->paginate(8);

        return view('users.articles.index', compact('articles', 'type', 'input'));
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
        $status = new Status();

        return view('users.articles.create',compact('status', 'user', 'blog'));
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

        $inputs = $request->all();
        $inputs['blog_id'] = $blog->id;
        $inputs['published_at'] = Carbon::create($inputs['published_year'], $inputs['published_month'], $inputs['published_day']);

        $article = Article::create($inputs);

        return redirect()->route('users.blogs.articles.edit', ['user' => $user_id, 'blog' => $blog_id, 'article' => $article->id])
                         ->with('success','エッセイの投稿を完了しました');
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
        $favorite = Favorite::firstOrNull(Auth::id(), $article_id);

        return view('articles.show',compact('article','user', 'favorite'));
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
        $status = new Status();

        if(Auth::guard('user')->user()->id === $user->id)
            return view('users.articles.edit', compact('user', 'article', 'status'));

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

        $inputs['published_at'] = Carbon::create($inputs['published_year'], $inputs['published_month'], $inputs['published_day']);

        $article->update($inputs);

        return redirect()->route('users.blogs.articles.edit', ['user' => $user_id, 'blog' => $blog_id, 'article' => $article_id])
                         ->with('success','エッセイの編集を完了しました');
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

        Article::destroy($article_id);

        // なぜかredirect()->back でも url()->previous() でも、現在のurlが取得されてしまう。
        // dd(url()->previous());
        $route = Auth::guard('user')->check() ? redirect()->route('users.blogs.show', ['user' => $user_id, 'blog' => $blog_id])
                                              : redirect()->route('admins.articles.index');

        return $route->with('success','エッセイ「'.$article->title.'」を削除しました');
    }
}
