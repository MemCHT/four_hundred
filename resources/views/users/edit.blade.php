@extends('layouts.user.with_sidemenu')

@section('content-with-sidemenu')
<div class="container">

    <h2 class="font-weight-bold mb-5">プロフィール設定</h2>

    <form action="{{ route('users.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group row align-items-center {{ $errors->has('icon') ? ' has-error' : '' }}">
            <label for="icon" class="control-label col-md-2">
                プロフィール画像
                <figure><img class="border" src="/images/icon/{{ $user->icon }}" alt="ユーザーアイコン" style="width:8em;border-radius:50%;"></figure>
            </label>
            <div class="col-md-10">
                <label for="icon" class="btn btn-outline-primary">画像を変更する</label>
                <input id="icon" type="file" class="" name="icon" accept="image/jpeg,image/png" hidden>

                @if ($errors->has('icon'))
                <span class="help-block">
                    <strong>{{ $errors->first('icon') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group mb-4 {{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="control-label">ユーザー名</label>

            <div>
                <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>

                @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <!-- 要処理追加 -->
        <div class="form-group mb-4">
            <label for="email" class="control-label">メールアドレス</label>

            <div>
                <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" required >

                @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <!-- 要処理追加 -->
        <div class="form-group mb-5">
            <label id="birthday" class="control-label">生年月日</label>

            <div id="birthday" class="row">
                <div class="col-md-2">
                    <input type="number" class="form-control" placeholder="yyyy">
                </div>
                <div class="">
                    <p>年</p>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" placeholder="mm">
                </div>
                <div class="">
                    <p>月</p>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" placeholder="dd">
                </div>
                <div class="">
                    <p>日</p>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <button type="submit" class="btn btn-primary col-md-2 offset-md-5">
                更新する
            </button>
        </div>
    </form>

</div>
@endsection
