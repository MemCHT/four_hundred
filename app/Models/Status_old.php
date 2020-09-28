<?php

namespace App\Models;

/**
 * Statusモデル
 * 
 * $status = ['公開' => 1, '非公開' => 2, '下書き' => 3]
 */
class Status_old
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

    /**
     * ステータス名を受け取って、ステータスidを返す。
     * 
     * @param string $name
     * '公開', '非公開', '下書き'
     * 
     * @return int
     */
    public static function getStatus($name){
        return self::$status[$name];
    }

    /**
     * ステータスの連想配列を渡す
     * 
     * @return array $status
     * ['公開' => 1, '非公開' => 2, '下書き' => 3]
     */
    public static function getStatuses(){
        return self::$status;
    }
}
