<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Statusモデル
 * 
 * $status = ['公開' => 1, '非公開' => 2, '下書き' => 3]
 */
class Status extends Model
{
    public $timestamps = false;

    protected $fillable = ['name','color'];

    //
    public function users(){
        return $this->hasMany(User::class,'status_id', 'id');
    }

    public function blogs(){
        return $this->hasMany(Blog::class,'status_id', 'id');
    }

    public function articles(){
        return $this->hasMany(Article::class, 'status_id', 'id');
    }

    /**
     * 非公開かどうか判定する(非公開ならtrue)
     * 
     * @param App\Models\Status $status
     * @return bool $isPrivate
     */
    public static function isPrivate($status){
        $isPrivate = $status->id === Status::where('name', '非公開')->first()->id;
        
        return $isPrivate;
    }
}
