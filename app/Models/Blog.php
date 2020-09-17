<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = ['user_id','title','status'];

    //
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function articles(){
        return $this->hasMany(Article::class, 'blog_id', 'id');
    }
}