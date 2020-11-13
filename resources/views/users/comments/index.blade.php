@extends('layouts.user.with_sidemenu')

@section('content-with-sidemenu')
<div class="container">

    <h4 class="font-weight-bold mb-5">
        <a href="{{ route('users.blogs.show', ['user' => $user->id, 'blog' => $user->blog->id]) }}" class="text-dark">< 記事管理</a>
    </h4>

    <h2>
        コメント管理
    </h2>

    <div>
        @foreach($comments as $comment)
            <div class="card">
                <div class="card-body">
                    <p>{{ $comment->user->name }}</p>
                    <p>{{ $comment->body }}</p>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
