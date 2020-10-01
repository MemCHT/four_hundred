@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="title-wrapper mt-5">
                <h3>ブログタイトル編集</h3>

                <form action="{{ route('users.blogs.update', ['user' => $user->id, 'blog' => $blog->id]) }}" method="POST">
                    @method('PUT')
                    @csrf

                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" name="title" value="{{ $errors->has('title') ? old('title') : $blog->title }}" placeholder="ブログ名を記入">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-outline-secondary">確定</button>
                            </div>
                        </div>
                        @component('components.error',['name' => 'title']) @endcomponent

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
                        <a class="btn btn-primary" href="#">編集</a>
                    </div>
                </div>
                @endforeach
            </div>

            {{$articles->links('vendor.pagination.modified')}}
        </div>
    </div>
</div>
@endsection