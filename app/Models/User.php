<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\JaPasswordReset;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function status(){
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function blog(){
        return $this->hasOne(Blog::class, 'user_id', 'id');
    }

    public function comments(){
        //return $this->belongsTo(Comment::class, 'id', 'user_id');
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function favorites(){
        //return $this->belongsTo('App\Models\Favorite');
        return $this->hasMany(Favorite::class, 'user_id', 'id');
    }

    /**
     * Userをidで取得
     * 
     * @param int $id
     * @return App\Models\User
     */
    public static function get($id){
        return User::find($id);
    }

    /**
     * sendPasswordResetNotification のオーバーライド
     *
     * @param  mixed $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new JaPasswordReset($token));
    }
}
