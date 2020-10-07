<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use \Socialite;
use \Auth;
use App\Models\User;
use App\Models\Blog;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * credentialsのオーバーライド
     *
     * @param  Request $request
     * @return Request $request
     */
    protected function credentials(Request $request)
    {
        // ステータスが1（凍結されていない）場合のみログイン可
        $request->merge(['status_id' => 1]);
        return $request->only($this->username(), 'password', 'status_id');
    }

    /**
     * twitterログイン用メソッド
     * twitterアプリに認証を求めに行く
     */
    public function redirectToTwitterProvider()
    {
        //dd(env('TWITTER_CLIENT_ID')."\n".env('TWITTER_CLIENT_SECRET'));
        return Socialite::driver('twitter')->redirect();
    }

    /**
     * twitterログイン用メソッド
     * twitterアプリからのレスポンスを取得して認証処理
     */
    public function handleTwitterProviderCallback(){
        try {
            $user = Socialite::with('twitter')->user();
            //throw new \Exception();
        }catch(\Exception $e){  // 例外をハンドリングしたらログインページにリダイレクトする。
            return redirect('/users/login')->with('oauth_error', 'SNSログインに失敗しました');
        }

        // 新規ユーザ時にusersテーブルに追加する属性
        $attributes = [
            'name' => $user->nickname,
            'email' => $user->getEmail()
        ];

        $myinfo = User::firstOrCreate(['token' => $user->token], $attributes);
        Auth::login($myinfo);
        return redirect()->route('users.profile.edit');
    }
}
