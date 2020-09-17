<?php

namespace App\Models;

/**
 * Statusモデル
 * 
 * $status = ['公開' => 1, '非公開' => 2, '下書き' => 3]
 */
class Status
{
    //Blog, Articleモデル共通ステータス
    private static $status = [
        '公開' => 1,
        '非公開' => 2,
        '下書き' => 3
    ];

    public static function count(){
        return count(self::$status);
    }
}
