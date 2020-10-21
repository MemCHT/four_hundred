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
use App\Models\IdentityProvider;
use PHPUnit\Framework\MockObject\Builder\Identity;

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
        $this->middleware('guest:user')->except('logout');
    }

    // Guardの認証方法を指定
    protected function guard()
    {
        return Auth::guard('user');
    }

    // ログイン画面
    public function showLoginForm()
    {
        return view('users.auth.login');
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        Auth::guard('user')->logout();

        return $this->loggedOut($request);
    }

    // ログアウトした時のリダイレクト先
    public function loggedOut(Request $request)
    {
        return redirect(route('users.login'));
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
     * facebookアプリに認証を求めに行く
     */
    public function redirectToFacebookProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * SNS認証アプリからのレスポンスを取得して認証
     * @param string $provider_name = [twitter|facebook]
     */
    public function handleProviderCallback($provider_name){
        try {
            $provider_user = Socialite::with($provider_name)->user();
            //throw new \Exception();
        }catch(\Exception $e){  // 例外をハンドリングしたらログインページにリダイレクトする。
            return redirect('/users/login')->with('oauth_error', 'SNSログインに失敗しました');
        }

        // 新規ユーザ時にusersテーブルに追加する属性
        $auth_attributes = [
            'name' => $provider_user->getName(),
            'email' => $provider_user->getEmail()    //emailがuniqueで重なってしまう問題 → email検索で対処
        ];
        
        //snsアカウント重複確認
        $sns_account = IdentityProvider::where('provider_name', $provider_name)
                                        ->where('provider_id', $provider_user->id)
                                        ->first();
        
        if(isset($sns_account) === false){

            //すでに登録済みのUserとemailが等しければ、そのUserにSNSアカウントを紐付ける。
            $auth_user = User::firstOrCreate(['email' => $provider_user->email], $auth_attributes);
            
            $provider_attributes = [
                'user_id' => $auth_user->id,
                'provider_id' => $provider_user->id,
                'provider_name' => $provider_name
            ];

            $sns_account = IdentityProvider::Create($provider_attributes);
        }
        
        Auth::login($sns_account->user);
        return redirect()->route('users.profile.edit')->with('success',$provider_name.'アカウントでサインアップしました。');
    }
}
