<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Admin;
use App\Models\Article;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\User;


class TestController extends Controller
{
    //
    public function index($index){
        $user = User::find($index);
        $admin = Admin::find($index);
        $article = Article::find($index);
        $blog = Blog::find($index);
        $comment = Comment::find($index);
        $favorite = Favorite::find($index);

        return view('/tests/model_get_test',compact('user','admin','article','blog','comment','favorite'));
    }
}
