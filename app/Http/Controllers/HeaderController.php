<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HeaderController extends Controller
{
    /**
     * ヘッダー検索フォームの送信先となる。
     * 検索先をURLによってブログ一覧/記事一覧に分ける。
     *
     * @param Request $request
     */
    private static $routes = ['blogs', 'articles'];
    public function search(Request $request){

        $url = explode('?', url()->previous())[0];
        $layers = explode('/', $url);

        // blogsまたはarticlesのレイヤーまで、レイヤー配列$layersを末尾から削除
        while( count($layers) !== 0 && in_array( end($layers), self::$routes ) === false )
            array_pop($layers);

        $redirect_path = count($layers) !== 0
                            ? implode("/", $layers)
                            //該当routeがない場合、ブログ一覧の検索へリダイレクト
                            : route('users.blogs.index', ['user' => Auth::guard()->user()->id]);

        $input = $request->input('keyword') ? self::createInputByKeyword( $request->input('keyword') ) : [];

        // 新着/人気順のtypeも格納
        $input['type'] = $request->input('type');
        // dd($input['type']);

        return redirect($redirect_path)->with('input', $input );
    }

    /**
     * 入力値$keywordから、'userName' => 'hoge'や 'title' => 'hoge' などテーブルカラムの形に変換
     * @param string $keyword
     * @return array $inputs = ['userName' => 'hoge', 'title' => 'hoge']
     */
    private static function createInputByKeyword($keyword){
        $keywords = explode(' ', $keyword);
        $input = [];

        foreach( $keywords as $keyword ){
            // ":" が含まれている場合は、それを境に $key => $value を入力値とする。
            if( str_contains($keyword, ":") ){

                [$key, $value] = explode(':', $keyword);
                $input[$key] = $value;
            }
            // そのほかは、"user_name" => $keyword を入力値とする
            else{
                $input['userName'] = $keyword;
            }
        }

        return $input;
    }
}
