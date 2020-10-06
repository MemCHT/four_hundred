@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="title-wrapper mt-5">
                <div class="row">
                    <div class="col-md-8">
                        <h2>{{$blog->title}}</h2>
                    </div>
                    <div class="col-md-4 text-right">
                        @if(Auth::id() === $blog->user->id)
                        <a class="btn btn-secondary" href="{{route('users.blogs.edit', ['user' => $blog->user_id, 'blog' => $blog->id])}}"> 編集 </a>
                        @endif
                    </div>
                </div>
            </div>


            <div class="card-wrapper mt-5">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="mb-3">{{$blog->user->name}}さんのエッセイ一覧</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        @if(Auth::id() === $blog->user->id)
                        <a class="btn btn-secondary" href=" {{route('users.blogs.articles.create', ['user' => $blog->user->id, 'blog' => $blog->id])}} ">エッセイを投稿する</a>
                        @endif
                    </div>
                </div>

                @foreach($articles as $article)
                <!-- ブログ所有者以外は、公開記事のみ閲覧可能 -->
                @if(Auth::id() === $blog->user->id || $article->status->name === '公開')
                <div class="card card-default mb-3">
                    <div class="card-header">
                        <div class="row article-favorite">
                            <div class="col-md-10">{{$article->title}}</div>
                            <div class="col-md-2 text-right">
                                @favorite
                                {{ count($article->favorites) }}
                                @endfavorite
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row article-status">
                            <div class="col-md-8">{{$article->updated_at}}</div>
                            <div class="col-md-4 text-right">
                                @status(['color' => $article->status->color])
                                {{ $article->status->name }}
                                @endstatus
                            </div>
                        </div>
                        <p>{{$article->body}}</p>
                        <a class="btn btn-primary" href="{{ route('users.blogs.articles.show', ['user' => $article->blog->user_id, 'blog' => $article->blog->id, 'article' => $article->id]) }}">エッセイ詳細</a>
                    </div>
                </div>
                @endif
                @endforeach
            </div>

            {{$articles->links('vendor.pagination.modified')}}
        </div>
    </div>
</div>
@endsection