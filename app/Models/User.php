<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use \InterventionImage;

use App\Notifications\JaPasswordReset;
use App\Models\Interfaces\AssurableRouteParameters;
use App\Models\Traits\AssurableRouteParametersTrait;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Follow;
use Exception;

use function PHPSTORM_META\map;

class User extends Authenticatable implements AssurableRouteParameters
{
    use Notifiable;
    use AssurableRouteParametersTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','token', 'status_id', 'birthday'
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

    protected $dates = [
        'birthday'
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

    public function follows(){
        return $this->hasMany(Follow::class, 'from_user_id', 'id');
    }

    public function followers(){
        return $this->hasMany(Follow::class, 'to_user_id', 'id');
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
            unset($inputs['icon']);
            self::where('id', $user_id)->update($inputs);
        } else {
            $filename = 'icon_'. $user_id. '.'. $inputs['icon']->getClientOriginalExtension();
            InterventionImage::make($inputs['icon'])
                ->fit(200, 200)
                ->save(public_path('/images/icon/' . $filename));
            $inputs['icon'] = $filename;

            self::where('id', $user_id)->update($inputs);
        }
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

    /**
     * ユーザインスタンスをuser_cardコンポーネント用にフォーマット（お気に入り・記事総数を追加）する
     * ※破壊的メソッド
     * @return App\Models\User
     */
    public function formatForUserCard(){

        $statuses = ['公開' => '正常', '非公開' => '凍結'];

        //記事全てのfavorite総数を取得
        $favorites_count = $this->blog->getFavoritesCount();
        $this->favorites_count = $favorites_count;

        //記事総数を取得
        $this->articles_count = $this->blog->getArticlesCount();

        $this->status->name = $statuses[$this->status->name];

        return $this;
    }

    /**
     * ユーザー一覧表示用オブジェクトを取得
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public static function getIndexObject(){

        $users = self::paginate(9);

        foreach($users as $user){
            $user->formatForUserCard();
        }

        return $users;
    }

    /**
     * name検索
     * @param  Illuminate\Database\Eloquent\Builder
     * @return  Illuminate\Database\Eloquent\Builder
     */
    private static function searchName($builder, $name){
        $builder->where('name', 'like',  "%$name%");

        return $builder;
    }

    /**
     * email検索
     * @param  Illuminate\Database\Eloquent\Builder
     * @return  Illuminate\Database\Eloquent\Builder
     */
    private static function searchEmail($builder, $email){
        $builder->where('email', 'like',  "%$email%");

        return $builder;
    }

    /**
     * 連想配列（キーと値）で検索する
     * @param  array  ['name' => 'hoge', 'email' => 'hoge']
     * @return  Illuminate\Database\Eloquent\Builder
     */
    private static $search_keys = ['name','email'];
    public static function search( $inputs ){
        $users = self::select('*');

        foreach($inputs as $key => $value){
            $method = 'search'.ucfirst($key);

            if(in_array($key, self::$search_keys))
                $users = self::$method($users, $value);
        }

        return $users;
    }

    /**
     * 対象ユーザをfollowできるかどうか判定
     *
     * @param int $other_user_id
     * @return bool
     */
    public function canFollow($other_user_id){

        return $this->id !== $other_user_id
                    && !Follow::where('from_user_id', $this->id)->where('to_user_id', $other_user_id)->exists();
    }

    /**
     * 対象ユーザをfollowしているか判定
     *
     * @param int $other_user_id
     * @return bool
     */
    public function isFollow($other_user_id){

        return Follow::where('from_user_id', $this->id)->where('to_user_id', $other_user_id)->exists();
    }
}
