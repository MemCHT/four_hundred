<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Blog;

class BlogController extends Controller
{

    /**
     * ブログのリストを取得するapi
     *
     * 【リクエストパラメータ】
     * | Name  | Value | Description
     * | sort  |string | 何順に取得するか指定する　※['newest']を指定可能
     * |offset |integer| 何番目から取得するか指定
     * |limit  |integer| 何件取得するか指定
     * |keyword| array | キーワードを指定して検索 → ['title' => 'hoge', 'userName' => 'hoge]
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        // $builder = Blog::select('*');
        $parameter = $request->input();

        $builder = Blog::search($parameter);
        $builder = Blog::buildToPublic($builder);

        // 個別でソーティング
        if(array_key_exists('sort', $parameter)){
            $sort_method = 'sort'.ucfirst($parameter['sort']);
            $builder = Blog::$sort_method($builder);
        }

        // ビルダー用のパラメータはまとめて処理
        $builder_parameter = ['offset', 'limit'];

        foreach($builder_parameter as $build){
            $builder = $builder->$build( $parameter[$build] );
        }

        $blogs = $builder->get();

        return $blogs;
    }

    /**
     * ブログidに応じてブログを取得するAPI
     *
     * @return \Illuminate\Http\Response
     */
    public function get($blog_id){
        return Blog::find($blog_id);
    }

    /**
     * ブログに紐づいたユーザーを取得するAPI
     *
     * @return \Illuminate\Http\Response
     */
    public function getUser($blog_id){
        $user = Blog::find($blog_id)->user;
        return $user;
    }
}
