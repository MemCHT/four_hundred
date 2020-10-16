<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\JaPasswordReset;
use \InterventionImage;

use App\Models\Blog;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','token'
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

    public function identityProviders(){
        return $this->hasMany(IdentityProvider::class, 'user_id', 'id');
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

    /**
     * profileUpdate ユーザー名とアイコンを更新する
     *
     * @param  array $inputs
     * @param  int $user_id
     * @return void
     */
    public static function profileUpdate($inputs, $user_id)
    {
        // iconが空(null)だったらnameのみupdate
        if(empty($inputs['icon'])) { // 空(null)の場合真
            self::where('id', $user_id)->update(['name' => $inputs['name']]);
        } else {
            $filename = 'icon_'. $user_id. '.'. $inputs['icon']->getClientOriginalExtension();
            InterventionImage::make($inputs['icon'])
                ->fit(200, 200)
                ->save(public_path('/images/icon/' . $filename));

            self::where('id', $user_id)->update(['name' => $inputs['name'], 'icon' => $filename]);
        }
    }

    /**
     * ルートパラメータに応じて、Userの存在チェック
     * 
     * @param  array  $params
     * @return bool
     */
    public static function isExist($params){
        if(isset($params['user'])){
            $user = self::find($params['user']);

            return isset($user);
        }
        return false;
    }

    /**
     * vendorに実装されているfirstOrCreateをオーバーライド
     * Blogを同時作成する処理を追加
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public static function firstOrCreate(array $attributes, array $values = [])
    {
        if (! is_null($instance = self::where($attributes)->first())) {
            return $instance;
        }

        return tap(self::make($attributes + $values), function ($instance) {
            $instance->save();

            // User作成時にBlogも同時作成   ※save()はupdate時にも使うので辞めたほうが良い。
            Blog::create([
                'user_id' => $instance->id,
                'title' => $instance->name."さんのブログ"
            ]);
        });
    }
}
