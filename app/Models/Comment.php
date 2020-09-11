<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['article_id','blog_id','body'];

    //
    public function article(){
        return $this->hasOne('App\Models\Article','id','article_id');
    }

    public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }
}
