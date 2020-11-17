@extends('layouts.user.with_sidemenu')

@section('content-with-sidemenu')
<div class="container">

    <h4 class="font-weight-bold mb-5">
        <a href="{{ route('users.blogs.show', ['user' => $user->id, 'blog' => $user->blog->id]) }}" class="text-dark">< 記事管理</a>
    </h4>

    <form action="{{ route('users.blogs.articles.update', ['user' => $user->id, 'blog' => $user->blog->id, 'article' => $article->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group mb-4">
            <label for="title" class="control-label">記事タイトル</label>

            <div>
                <input id="title" type="text" class="form-control" name="title" value="{{ $article->title }}" required autofocus placeholder="記事タイトル">
            </div>

            @include('components.error', ['name' => 'title'])
        </div>

        <!-- 要処理追加 -->
        <div class="form-group mb-5">
            <label for="releaseDay" class="control-label">公開日</label>

            <div id="releaseDay" class="row">
                <div class="col-md-2">
                    <input type="number" class="form-control" name="published_year" value={{ old('published_year') ? old('published_year') : $article->published_at->format('Y') }} required placeholder="yyyy">
                </div>
                <div class="">
                    <p>年</p>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" name="published_month" value={{ old('published_month') ? old('published_month') : $article->published_at->format('m') }} required placeholder="mm">
                </div>
                <div class="">
                    <p>月</p>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" name="published_day" value={{ old('published_day') ? old('published_day') : $article->published_at->format('d') }} required placeholder="dd">
                </div>
                <div class="">
                    <p>日</p>
                </div>
            </div>

            @include('components.error', ['name' => 'published_year'])
            @include('components.error', ['name' => 'published_month'])
            @include('components.error', ['name' => 'published_day'])
        </div>

        <!-- 要処理追加 -->
        <div class="form-group mb-4">
            <label for="body" class="control-label">本文</label>

            <div>
                <textarea id="body" type="body" class="form-control" name="body" rows="10" required >{{ $article->body }}</textarea>
            </div>
            <p class="text-right">xxx/xxx文字</p>
            @include('components.error', ['name' => 'body'])
        </div>

        <div class="form-group d-flex justify-content-center col-md-12">
            <button type="submit" name="status_id" value="{{ $status::getByName('非公開')->id }}" class="btn btn-outline-secondary col-md-2">
                非公開にする
            </button>
            <div class="pr-5"></div>
            <button type="submit" name="status_id" value="{{ $status::getByName('公開')->id }}" class="btn btn-primary col-md-2">
                公開する
            </button>
        </div>
    </form>

</div>
@endsection
