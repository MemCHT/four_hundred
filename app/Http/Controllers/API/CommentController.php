<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;

class CommentController extends Controller
{
    /**
     * 記事に紐づくコメント（複数）を取得
     */
    public function list(Article $article){
        // return $article->id;
        return $article->validComments();
    }
}
