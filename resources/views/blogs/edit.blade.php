@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="title-wrapper mt-5">
                <h3>ブログ編集</h3>

                <form action="{{ route('users.blogs.update', ['user' => $blog->user_id, 'blog' => $blog->id]) }}" method="POST">
                    @method('PUT')
                    @csrf

                    <div class="form-group">
                        <label>タイトル</label>
                        <input type="text" class="form-control" name="title" value="{{ $errors->has('title') ? old('title') : $blog->title }}" placeholder="ブログ名を記入">
                        @component('components.error',['name' => 'title']) @endcomponent
                    </div>

                    <div class="form-group">
                        <label>ステータス</label>
                        <select class="form-control" name="status_id">
                            @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ $blog->status_id === $status->id ? "selected" : "" }} style="opacity:0.5">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">確定</button>
                        <a class="btn btn-secondary" onclick="history.back();">キャンセル</a>
                    </div>
                </form>
            </div>


            <div class="card-wrapper mt-5">
                <h3 class="mb-3">{{$blog->user->name}}さんのエッセイ一覧</h3>

                @foreach($articles as $article)
                <div class="card card-default mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">{{$article->title}}</div>
                            <div class="col-md-2 text-right">
                                @favorite
                                {{ count($article->favorites) }}
                                @endfavorite
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">{{$article->updated_at}}</div>
                            <div class="col-md-4 text-right">
                                @status(['color' => $article->status->color])
                                {{ $article->status->name }}
                                @endstatus
                            </div>
                        </div>
                        <p>{{$article->body}}</p>
                        <a class="btn btn-primary" href="{{ route('users.blogs.articles.edit',['user' => $article->blog->user_id, 'blog' => $article->blog_id, 'article'=>$article->id]) }}">編集</a>
                    </div>
                </div>
                @endforeach
            </div>

            {{$articles->links('vendor.pagination.modified')}}
        </div>
    </div>
</div>
@endsection