@extends('layouts.user.with_sidemenu')

@section('content-with-sidemenu')
    <div class="container">
        <h2 class="mb-5 font-weight-bold">ブログ管理</h2>
        <form action="{{ route('users.blogs.update', ['user'=>$user->id, 'blog'=>$blog->id]) }}" method="POST">
            @method("PUT")
            @csrf

            <div class="form-group mb-4">
                <label for="title" class="control-label">ブログタイトル</label>

                <input id="title" type="text" class="form-control" name="title" value="{{ $blog->title }}" required>

                @include('components.error', ['name' => 'title'])
            </div>

            <div class="form-group mb-5">
                <label for="overview" class="control-label">ブログ説明文</label>

                @include('components.textarea_with_count', [
                    'textarea_attributes' => [
                        'id' => 'overview',
                        'type' => 'text',
                        'class' => 'form-control',
                        'name' => 'overview',
                        'rows' => '4',
                        'required' => 'true',
                        'value' => $blog->overview
                    ],
                    'max_count' => 400
                ])

                @include('components.error', ['name' => 'overview'])
            </div>

            <div class="form-group row">
                <button type="submit" class="btn btn-primary col-md-2 offset-md-5">更新する</button>
            </div>
        </form>
    </div>
@endsection
