@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <p class="lead text-center">four hundredを始めてエッセイを書こう。</p>
            <div class="card">
                <div class="card-header">four hundredにログイン</div>

                <div class="card-body">
                    @if(session('oauth_error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('oauth_error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div> -->

                        <div class="form-group row mb-3">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    ログイン
                                </button>
                            </div>
                        </div>

                        <div class="form-group row" style="align-items: center;">
                            <label for="password" class="col-md-4 col-form-label text-md-right">SNSログイン</label>

                            <div class="col-md-6">
                                <!-- <ul>
                                    <li><a href=""><img src="/images/login/icon_twitter.png" alt="Twitterでログイン"></a></li>
                                    <li><a href=""><img src="/images/login/icon_facebook.png" alt="Facebookでログイン"></a></li>
                                </ul> -->

                                <a class="btn btn-link" href="{{ route('users.login.twitter') }}">
                                    <img src="/images/login/icon_twitter.png" alt="Twitterでログイン">
                                </a>
                                <a class="btn btn-link" href="">
                                    <img src="/images/login/icon_facebook.png" alt="Facebookでログイン">
                                </a>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <div class="col-md-8 offset-md-4">
                                <a class="btn btn-link border-right" href="{{ route('users.password.request') }}">
                                    パスワードをお忘れですか？
                                </a>
                                <a class="btn btn-link" href="{{ route('users.register') }}">
                                    アカウント作成
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
