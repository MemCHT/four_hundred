@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="article-form-wrapper mt-5">
                <h3>エッセイの編集</h3>

                <form action="{{ route('users.blogs.articles.update',['user' => $user->id, 'blog' => $user->blog->id, 'article' => $article->id]) }}" method="POST">
                    @method('PUT')
                    @csrf

                    <div class="form-group col-md-4">
                        <div>
                            <label for="" >ステータス</label>
                        </div>

                        <select class="form-control" name="status_id">
                            @foreach($statuses as $status)

                                @if($errors->has('status_id'))
                                <option value="{{ $status->id }}" {{ $status->id == old('status_id') ? 'selected' : '' }}>{{ $status->name }}</option>
                                @else
                                <option value="{{ $status->id }}" {{ $status->name == $article->status->name ? 'selected' : '' }}>{{ $status->name }}</option>
                                @endif

                            @endforeach
                        </select>

                        @component('components.error',['name' => 'status_id']) @endcomponent
                    </div>

                    <div class="form-group col-md-12">
                        <label for="">タイトル（40文字まで）</label>

                        <input type="text" class="form-control" name="title" value="{{ $errors->has('title') ? old('title') : $article->title }}" placeholder="エッセイのタイトルを記入">

                        @component('components.error',['name' => 'title']) @endcomponent
                    </div>

                    <div class="form-group col-md-12">
                        <label for="">本文（390文字から400文字）</label>
                        
                        <textarea class="form-control" name="body" rows="10" placeholder="本文を記入">{{ $errors->has('body') ? old('body') : $article->body }}</textarea>

                        @component('components.error',['name' => 'body']) @endcomponent

                    </div>

                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-primary">確定</button>
                        <button class="btn btn-danger btn-delete">
                            エッセイを削除する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@component('components.popup_delete',[
    'route' => route('users.blogs.articles.destroy',['user' => $user->id, 'blog' => $user->blog->id, 'article' => $article->id])
])
@endcomponent
@endsection