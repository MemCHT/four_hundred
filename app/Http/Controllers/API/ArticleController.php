<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * 記事のリストを取得するapi用コントローラ
     *
     * 【リクエストパラメータ】
     * | Name  | Value | Description
     * | sort  |string | 何順に取得するか指定する　※['newest']を指定可能
     * |offset |integer| 何番目から取得するか指定
     * |limit  |integer| 何件取得するか指定
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request, $blog_id)
    {
        $builder = Article::where('blog_id', $blog_id);

        $parameter = $request->input();

        // 個別でソーティング
        if(array_key_exists('sort', $parameter)){
            $sort_method = 'sort'.ucfirst($parameter['sort']);
            $builder = Article::$sort_method($builder);
        }

        // ビルダー用のパラメータはまとめて処理
        $builder_parameter = ['offset', 'limit'];

        foreach($builder_parameter as $build){
            $builder = $builder->$build( $parameter[$build] );
        }

        $blogs = $builder->get();

        return $blogs;
    }
}
