@extends('layouts.admin.app')

@section('content')
<div class="container">
    <div class="row justify-content-center d-flex flex-column align-items-center" style="height:80vh;">
        <div class="col-md-4">
            <div class="">
                <h2 class="text-center font-weight-bold text-secondary">管理者ログイン</h2>
                <div class="card">
                    <div class="card-body">
                        @if(session('oauth_error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('oauth_error') }}
                        </div>
                        @endif

                        <form method="POST" action="{{ route('admins.login') }}">
                            @csrf

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus
                                        placeholder="メールアドレスを入力">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password" placeholder="パスワードを入力">

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
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        ログイン
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection