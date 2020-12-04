<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Status;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Illuminate\Http\Request;
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $articles = Article::searchArticlesByKeyword($request);
        $articles = Article::search($request->input());
        $articles = $articles->paginate(8);
        $statuses = Status::all();
        $current_status = Status::find($request->session()->get('status_id'));

        return view('admins.articles.index', compact('articles', 'statuses', 'current_status'));
    }

    /**
     * Destroy an article
     *
     * @param int $article_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($article_id){
        $article = Article::find($article_id);
        $article->delete();

        return back()->with('success', '記事「'.$article->title.'」を削除しました。');
    }
}
