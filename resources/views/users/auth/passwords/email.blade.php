@extends('layouts.user.app')

@section('content')
<div class="container auth-view">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <!-- <div class="card-header">パスワードリセット</div> -->
                <h2 class="text-center font-weight-bold">パスワードリセット</h2>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-12 col-form-label text-md-left">メールアドレス</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0 mt-5">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">
                                    パスワードリセットリンクを送信
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @include('components.sns_login')
        </div>
    </div>
</div>
@endsection
