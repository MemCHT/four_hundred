<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('users.auth.passwords.email');
    }

    /**
     * credentialsのオーバーライド
     *
     * @param  Request $request
     * @return Request $request
     */
    protected function credentials(Request $request)
    {
        // ステータスが1（凍結されていない）場合のみパスワードリセット可能
        $request->merge(['status_id' => 1]);
        return $request->only('email', 'status_id');
    }
}
