<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait AssurableRouteParametersTrait
{

    static protected $root = 'user';

    /**
     * 指定されたパラメータが正しいかチェックするためのメソッド
     * 
     * 子モデル→親モデルのつながりが正しければtrue
     * @param array params = ['子モデル' => xx , '親モデル' => xx], ex) ['blog'=>xx, 'user'=>xx]
     * @return bool
     */
    public static function isExistParent($params, $currentUrl){

        // 現レイヤー、親レイヤー、親Urlを取得
        [$currentLayer, $parentLayer, $parentUrl] = self::getCurrentAndParentLayerAndParentUrl($currentUrl);

        // 現レイヤーがrootレイヤならチェック必要なし
        if($currentLayer == self::$root)
            return true;

        //dd([$currentLayer, $parentLayer]);

        // 現レイヤー → 親レイヤーのつながりチェック
        if(isset($params[$currentLayer]) && isset($params[$parentLayer])){
            $currentInstance = self::find($params[$currentLayer]);

            // 親レイヤーに対応したid名, ex) blog_id
            $parentId = $parentLayer.'_id';

            if($currentInstance && $currentInstance->$parentId == $params[$parentLayer]){
                
                // 親レイヤー, ex) $currentInstance->blog
                $parentInstance = $currentInstance->$parentLayer;
                //dd($parentInstance);
                return $parentInstance::isExistParent($params, $parentUrl);
            }
        }
        return false;
    }

    /**
     * 現在・親のレイヤー名、親のUrlを取得
     * 
     * @param string $currentUrl
     * @return array [$currentLayer, $parentLayer]
     */
    private static function getCurrentAndParentLayerAndParentUrl($currentUrl){
        // レイヤーを配列に分ける ex) [localhost,users,1,blogs,1] 
        $layers = explode('/', $currentUrl);

        // index, create等最下層がパラメータではないもののcurrentLayerを一つ上げるため、予め最下層を取り除く
        array_pop($layers);
        
        // 現在のレイヤーを取り出す ex) blogs
        while( preg_match('/^[a-zA-Z]+$/', $currentLayer = array_pop($layers) ) == false );

        // 親レイヤーのurlを保持 ex) localhost/users/1
        $parentUrl = implode('/', $layers);
        
        // 親レイヤーを取り出す ex) users
        while( preg_match('/^[a-zA-Z]+$/', $parentLayer = array_pop($layers) ) == false );

        // 単数形化して代入 ex)[blog, user]
        // ※アロー関数はphp7.4から、現環境はphp7.3
        $resultLayers = array_map( function($layer){
            return Str::singular($layer);
        }, [$currentLayer, $parentLayer]);

        $results = array_merge($resultLayers, [$parentUrl]);

        return $results;
    }
}

?>