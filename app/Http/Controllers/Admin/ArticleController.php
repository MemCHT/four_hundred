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
        $articles = Article::searchArticlesByKeyword($request);
        $statuses = Status::all();
        $current_status = Status::find($request->session()->get('status_id'));

        return view('admins.articles.index', compact('articles', 'statuses', 'current_status'));
    }
}
