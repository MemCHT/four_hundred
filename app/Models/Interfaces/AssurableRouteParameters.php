<?php

namespace App\Models\Interfaces;

interface AssurableRouteParameters
{

    /**
     * 指定されたパラメータが正しいかチェックするためのメソッド
     * 
     * 子モデル→親モデルのつながりが正しければtrue
     * @param array params = ['子モデル' => xx , '親モデル' => xx]
     * @return bool
     */
    public static function isExistParent($param, $currentUrl);
}

?>