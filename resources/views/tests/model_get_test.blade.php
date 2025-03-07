@extends('layouts.user.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">
                    ※各々のid=1 のインスタンスを取得して表示しています。
                </div>
                <div class="card-body">
                    <h4>Userモデル</h4>
                    <ul class="list-group">
                        <li class="list-group-item">user->status <br> {{ $user->status }}</li><br>
                        <li class="list-group-item">user->blog <br> {{ $user->blog }}</li><br>
                        <li class="list-group-item">
                            @foreach($user->comments as $key => $comment)
                            user->comments[{{$key}}] <br> {{ $comment }}
                            <br><br>
                            @endforeach
                        </li>
                        <li class="list-group-item">
                            @foreach($user->favorites as $key => $favorite)
                            user->favorites[{{$key}}] <br> {{ $favorite}}
                            <br><br>
                            @endforeach
                        </li>
                    </ul>
                    <br>

                    <h4>Blogモデル</h4>
                    <ul class="list-group">
                        <li class="list-group-item">blog->user <br> {{ $blog->user }}</li><br>
                        <li class="list-group-item">blog->status <br> {{ $blog->status }}</li><br>
                        <li class="list-group-item">
                            @foreach($blog->articles as $key => $article)
                            blog->articles[{{$key}}] <br> {{ $article }}
                            <br><br>
                            @endforeach
                        </li>
                    </ul>
                    <br>

                    <h4>Articleモデル</h4>
                    <ul class="list-group">
                        <li class="list-group-item">article->blog <br> {{ $article->blog }}</li><br>
                        <li class="list-group-item">article->status <br> {{ $article->status }}</li><br>
                        <li class="list-group-item">
                            @foreach($article->comments as $key => $comment)
                            article->comments[{{$key}}] <br> {{ $comment }}
                            <br><br>
                            @endforeach
                        </li>
                        <li class="list-group-item">
                            @foreach($article->favorites as $key => $favorite)
                            article->favorites[{{$key}}] <br> {{ $favorite }}
                            <br><br>
                            @endforeach
                        </li>
                    </ul>
                    <br>

                    <h4>Commentモデル</h4>
                    <ul class="list-group">
                        <li class="list-group-item">comment->article <br> {{ $comment->article }}</li><br>
                        <li class="list-group-item">comment->user <br> {{ $comment->user }}</li><br>
                    </ul>
                    <br>

                    <h4>Favoriteモデル</h4>
                    <ul class="list-group">
                        <li class="list-group-item">favorite->article <br> {{ $favorite->article }}</li><br>
                        <li class="list-group-item">favorite->user <br> {{ $favorite->user }}</li><br>
                    </ul>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
