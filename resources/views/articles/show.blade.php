@extends('layouts.app')

<style type="text/css">
    .article-wrapper .article-body{
        min-height:20em;
    }

    .comment-wrapper .comment{
        margin-left:0;
    }
    .comment-header-left img{
        width: 37px;
        background-color:white;
        border-radius: 50%;
    }
    .comment-header-left p{
        display: inline-block;
    }
    .comment-header-right p{
        display: inline-block;
    }
    .comment-wrapper .comment-title{
        border-bottom:1px solid #707070;
    }
    .comment-wrapper .comment-body{
        border-bottom:1px dotted #707070;
    }

</style>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="article-wrapper row mt-5">
                <div class="col-md-8">
                    <h2>{{$article->title}}</h2>
                </div>
                <div class="col-md-4 text-right">
                    @if(Auth::id() === $article->blog->user->id)
                    <a class="btn btn-secondary" href="{{route('users.blogs.articles.edit', ['user' => $article->blog->user_id, 'blog' => $article->blog_id, 'article' => $article->id])}}">
                        編集
                    </a>
                    @endif
                </div>
                <div class="col-md-12">
                    @favorite
                    {{ count($article->favorites) }}
                    @endfavorite
                </div>

                <div class="article-body col-md-12 mt-2">
                    <p>{{ $article->body }}</p>
                </div>
            </div>

            <div class="comment-wrapper row mt-5">
                <div class="comment-title col-md-12">
                    <h3 class="">コメント</h3>
                </div>

                @foreach($article->comments as $comment)
                <div class="comment row col-md-12 mt-2">
                    <div class="comment-header-left col-md-6">
                        <img class="user-icon" src="{{asset('/images/icon').'/'.$comment->user->icon}}" alt="ユーザー画像">
                        <p>{{ $comment->user->name }}</p>
                    </div>
                    <div class="comment-header-right col-md-6 text-right">
                        <p>{{ $comment->created_at }}</p>
                        <button id="btn-delete_{{ $comment->id }}" class="btn btn-secondary btn-delete"> 削除 </button>
                    </div>
                    <div class="comment-body col-md-12">
                        <p>{{ $comment->body }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            @if($user)
            <div class="comment-form-wrapper row mt-3">
                <p class="col-md-12"><strong>コメントを書く</strong></p>

                <form class="col-md-12" action="{{ route('users.blogs.articles.comments.store',['user' => $article->blog->user_id, 'blog' => $article->blog_id, 'article' => $article->id]) }}">
                    <div class="form-group">
                        <label for="">名前：{{ $user->name }}</label>
                        <textarea class="form-control" name="body" rows="3" placeholder="コメントを記入"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">送信</button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@include('components.popup_delete',[
    'route' => route('users.blogs.articles.comments.destroy',['user' => $user->id, 'blog' => $user->blog->id, 'article' => $article->id, 'comment' => 0])
])