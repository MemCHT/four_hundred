<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait AssurableRouteParametersTrait
{

    // 全ての親となるレイヤーを設定
    static protected $root = 'user';

    /**
     * 指定されたパラメータが正しいかチェックするためのメソッド
     * 
     * 子モデル→親モデルのつながりが正しければtrue
     * @param array params(ルートパラメータが入る) = ['現レイヤー' => xx , '親レイヤー' => xx], ex) ['blog'=>xx, 'user'=>xx]
     * @return bool
     */
    public static function isExistParent($params, $currentUrl){

        // 1. 現レイヤー、親レイヤー、親Urlを取得
        [$currentLayer, $parentLayer, $parentUrl] = self::getCurrentAndParentLayerAndParentUrl($currentUrl);

        // ※現レイヤーがrootならチェック必要なし
        if($currentLayer == self::$root)
            return true;

        // 2. 現レイヤー → 親レイヤーのつながりチェック
        if(isset($params[$currentLayer]) && isset($params[$parentLayer])){
            
            // パラメータから現レイヤー取得
            $currentInstance = self::find($params[$currentLayer]);
            
            // 親レイヤーに対応したid名, ex) blog_id
            $parentId = $parentLayer.'_id';

            // パラメータからインスタンスを取得でき、それが親レイヤーとつながっているかチェック
            if($currentInstance && $currentInstance->$parentId == $params[$parentLayer]){
                
                // 親レイヤー, ex) $currentInstance->blog
                $parentInstance = $currentInstance->$parentLayer;

                // 親レイヤーに処理を伝搬させる
                return $parentInstance::isExistParent($params, $parentUrl);
            }
        }
        return false;
    }

    /**
     * 現在・親のレイヤー名、親のUrlを取得
     * 
     * @param string $currentUrl
     * @return array [$currentLayer, $parentLayer, $parentUrl]
     */
    private static function getCurrentAndParentLayerAndParentUrl($currentUrl){

        // $currentUrl ex) http://localhost/users/1/blogs/1

        // 1. レイヤーを配列に分ける ex) ["http:", "", "localhost", "users", "1", "blogs", "1"] 
        $layers = explode('/', $currentUrl);

        // 2. index, create等最下層がパラメータではないもののcurrentLayerを一つ上げるため、予め最下層を取り除く
        if(array_pop($layers) === 'create')
            array_pop($layers);
        
        // 3. 現在のレイヤーを取り出す ex) blogs
        while( preg_match('/^[a-zA-Z]+$/', $currentLayer = array_pop($layers) ) == false );

        // 4. 親レイヤーのurlを保持 ex) http://localhost/users/1
        // ※親レイヤーに処理を伝播させるため
        $parentUrl = implode('/', $layers);
        
        // 5. 親レイヤーを取り出す ex) users
        while( preg_match('/^[a-zA-Z]+$/', $parentLayer = array_pop($layers) ) == false );

        // 6. 単数形化して結果として保存 ex)[blog, user]
        // ※アロー関数はphp7.4から、現環境はphp7.3
        $resultLayers = array_map( function($layer){
            return Str::singular($layer);
        }, [$currentLayer, $parentLayer]);

        $results = array_merge($resultLayers, [$parentUrl]);

        return $results;
    }
}

?>