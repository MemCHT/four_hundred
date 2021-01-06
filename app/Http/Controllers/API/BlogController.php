<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Blog;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function index(Request $request)
    {
        // dd($request->input());
        // $blogs = Blog::select('*')->offset(1)->limit(4)->get();
        $blogs = Blog::select('*')->offset($request->input('offset'))->limit(4)->get();
        // TODO_view側に必要になるデータを追加（JOIN?）する。

        return $blogs;
    }*/

    /**
     * ブログのリストを取得するapi
     *
     * 【リクエストパラメータ】
     * | Name  | Value | Description
     * | sort  |string | 何順に取得するか指定する　※['newest']を指定可能
     * |offset |integer| 何番目から取得するか指定
     * |limit  |integer| 何件取得するか指定
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $builder = Blog::select('*');

        $parameter = $request->input();

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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
