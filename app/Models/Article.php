<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['blog_id','title','body','status'];

    //
    public function blog(){
        return $this->hasOne('App\Models\Blog','id','blog_id');
    }
}
