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
        'name', 'email', 'password','token', 'status_id'
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
    /*public static function isExist($params){
        if(isset($params['user'])){
            $user = self::find($params['user']);

            return isset($user);
        }
        return false;
    }*/

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
     * ユーザ一覧表示用インスタンスを取得
     * @param  Illuminate\Http\Request
     * @return  Illuminate\Pagination\LengthAwarePaginator (article)
     */
    public static function searchUserByKeyword($request){
        $users = User::select('*');

        $keyword = $request->input('keyword');
        $session_has_keyword = $request->session()->has('keyword');
        $request_has_page = $request->has('page');

        // 1. 検索もページ移動もしていないとき、セッションを破棄する。（ヘッダから直接飛んだ時）
        if(isset($keyword) == false && $request_has_page == false){
            $request->session()->forget('keyword');
        }

        // 2. 検索後にページボタン押下時、セッションからkeywordを取得
        if( $session_has_keyword && $request_has_page)
            $keyword = $request->session()->get('keyword');
        
        // 3. キーワード（name, email）によって検索処理
        if(isset($keyword)){
            $users->where('name', 'like', '%'.$keyword.'%')->orWhere('email', 'like', '%'.$keyword.'%');
            $request->session()->put('keyword', $keyword);
        }
        $users = $users->orderBy('updated_at', 'DESC')->paginate(9);

        foreach($users as $user){
            $user->formatForUserCard();
        }

        return $users;
    }
}
