@extends('layouts.user.with_sidemenu')

@section('content-with-sidemenu')
    <div class="container">
        <h2 class="mb-5 font-weight-bold">ブログ管理</h2>
        <form action="{{ route('users.blogs.update', ['user'=>$user->id, 'blog'=>$blog->id]) }}" method="POST">
            @method("PUT")
            @csrf

            <!-- 要処理追加 -->
            <div class="form-group mb-4">
                <label for="title" class="control-label">ブログタイトル</label>
                <input id="title" type="text" class="form-control" name="title" value="{{ $blog->title }}">
            </div>

            <!-- 要処理追加 -->
            <div class="form-group mb-5">
                <label for="overview" class="control-label">ブログ説明文</label>
                <textarea id="overview" type="text" class="form-control" name="overview" rows="4">{{ "新しく追加する必要がありま～～～～す" }}</textarea>
            </div>

            <div class="form-group row">
                <button type="submit" class="btn btn-primary col-md-2 offset-md-5">更新する</button>
            </div>
        </form>
    </div>
@endsection