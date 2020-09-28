@extends('layouts.app')

@section('content')
<div class="container">

    <h2>プロフィール設定</h2>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="control-label">ユーザーネーム</label>

            <div>
                <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>

                @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('icon') ? ' has-error' : '' }}">
            <label for="icon" class="control-label">
                アイコン（5MBまでのjpg,png）
                <figure><img class="border" src="/images/icon/{{ $user->icon }}" alt="ユーザーアイコン"></figure>
            </label>
            <div>
                <input id="icon" type="file" class="" name="icon" accept="image/jpeg,image/png">

                @if ($errors->has('icon'))
                <span class="help-block">
                    <strong>{{ $errors->first('icon') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                更新
            </button>
        </div>
    </form>

</div>
@endsection
