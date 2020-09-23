<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
