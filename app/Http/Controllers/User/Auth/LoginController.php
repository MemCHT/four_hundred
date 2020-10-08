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
     * twitterアプリに認証を求めに行く
     */
    public function redirectToTwitterProvider()
    {
        return Socialite::driver('twitter')->redirect();
    }

    /**
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
            'email' => $user->email    //emailがuniqueで重なってしまう問題  email検索で対処
        ];

        //email確認後、firstOrCreate()
        $registered = User::where('email', $user->email)->first();
        $myinfo = isset($registered) ? $registered : User::firstOrCreate(['token' => $user->token], $attributes);
        
        Auth::login($myinfo);
        return redirect()->route('users.profile.edit')->with('success','twitterアカウントでサインアップしました。');
    }

    /**
     * facebookアプリに認証を求めに行く
     */
    public function redirectToFacebookProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * facebookアプリからのレスポンスを取得して認証処理
     */
    public function handleFacebookProviderCallback(){
        try {
            $user = Socialite::with('facebook')->user();
            //throw new \Exception();
        }catch(\Exception $e){  // 例外をハンドリングしたらログインページにリダイレクトする。
            return redirect('/users/login')->with('oauth_error', 'SNSログインに失敗しました');
        }

        // 新規ユーザ時にusersテーブルに追加する属性
        $attributes = [
            'name' => $user->name,
            'email' => $user->email     // emailがuniqueで重なってしまう問題　email検索で対処
                                        // アカウントに直接紐づけなど行っていない状態で、複数SNSアカウント間の判別はemail以外で不可
                                            // →サインアップ画面ではemail || tokenで判断。その後の紐づけによって別アカウントログイン可能にできそう（追加機能？）。
        ];
        $token = $user->id.'-'.substr($user->token,0,14);   // facebookのトークンは15文字以降が毎回変わっていたので、token = id+トークン先頭14文字 とした。

        //email確認後、firstOrCreate()
        $registered = User::where('email', $user->email)->first();
        $myinfo = isset($registered) ? $registered : User::firstOrCreate(['token' => $token], $attributes);

        Auth::login($myinfo);
        return redirect()->route('users.profile.edit')->with('success','facebookアカウントでサインアップしました。');
    }
}
