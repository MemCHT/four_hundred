<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['blog_id','title','body','status'];

    //
    public function blog(){
        return $this->belongsTo(Blog::class, 'blog_id', 'id');
    }

    public function comments(){
        return $this->hasMany(Comment::class, 'article_id', 'id');
    }

    public function favorites(){
        return $this->hasMany(Favorite::class, 'article_id', 'id');
    }
}
