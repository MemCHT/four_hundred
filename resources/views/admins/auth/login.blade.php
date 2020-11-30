@extends('layouts.admin.app')

@section('content')
<div class="container auth-view">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <h2 class="text-center font-weight-bold">ログイン</h2>
            <div class="card">
                <!-- <div class="card-header">four hundredにログイン</div> -->

                <div class="card-body">
                    @if(session('oauth_error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('oauth_error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admins.login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-12 col-form-label text-md-left">メールアドレス</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="exapmle@exapmle.com">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-12 col-form-label text-md-left">パスワード</label>

                            <div class="col-md-12">
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

                        <div class="form-group row mt-5">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary pl-5 pr-5">
                                    ログイン
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!-- include('components.sns_login') -->
        </div>
    </div>
</div>
@endsection
