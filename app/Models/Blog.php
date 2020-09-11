<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = ['user_id','title','status'];

    //
    public function blog(){
        return $this->hasOne('App\Models\User','id','user_id');
    }
}