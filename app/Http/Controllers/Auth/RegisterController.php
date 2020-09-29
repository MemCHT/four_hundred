<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Mail\RegisterEmail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default thiscontroller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * フォームに入力されたアカウント情報をセッションに保存
     *
     * @param  \Illuminate\Http\Request  $request
     */
    private $formItems = ['name', 'email', 'password', 'password_confirmation'];
    public function post(Request $request)
    {
        $input = $request->only($this->formItems);

        $this->validator($input)->validate();

        // セッションに書き込む
        $request->session()->put('form_input', $input);

        return redirect()->route('register.confirm');
    }

    /**
     * confirm
     *
     * @param  \Illuminate\Http\Request  $request
     */
    function confirm(Request $request){
        $input = $request->session()->get('form_input');

        // セッションに値が無い時はフォームに戻る
        if(!$input){
            return redirect()->route('register');
        }

        return view('auth.register_confirm', [
            'input' => $input,
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // セッションにデータを登録しているので、バリデーションは行わない
        // $this->validator($request->all())->validate();

        $input = $request->all();
        // セッションの存在チェック
        if(session()->has('form_input')) {
            $tmp = session('form_input');

            $input['name'] = $tmp['name'];
            $input['email'] = $tmp['email'];
            $input['password'] = $tmp['password'];
            session()->forget('form_input');
        } else {
            // セッションが存在しなければリダイレクト
            return redirect()->route('register');
        }

        event(new Registered($user = $this->create($input)));

        $this->guard()->login($user);

        // 登録完了メール送信
        Mail::to($request->user())->send(new RegisterEmail($input));

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }
}
