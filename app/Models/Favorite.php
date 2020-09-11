<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = ['article_id','user_id','status'];

    //
    public function article(){
        return $this->hasOne('App\Models\Article','id','article_id');
    }

    public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }
}
