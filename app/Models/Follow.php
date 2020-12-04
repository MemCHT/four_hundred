<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $fillable = ['from_user_id', 'to_user_id'];
    public $timestamps = false;
}
