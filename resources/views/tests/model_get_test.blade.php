<div class="panel panel-default">
    <div class="panel-header">
        ※各々のid=1 のインスタンスを取得して表示しています。 
    </div>
    <div class="panel-body">
        Userモデル
        <ul class="list-group">
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

        Blogモデル
        <ul class="list-group">
            <li class="list-group-item">blog->user <br> {{ $blog->user }}</li><br>
            <li class="list-group-item">
                @foreach($blog->articles as $key => $article)
                blog->articles[{{$key}}] <br> {{ $article }}
                <br><br>
                @endforeach
            </li>
        </ul>
        <br>

        Articleモデル
        <ul class="list-group">
            <li class="list-group-item">article->blog <br> {{ $article->blog }}</li><br>
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

        Commentモデル
        <ul class="list-group">
            <li class="list-group-item">comment->article <br> {{ $comment->article }}</li><br>
            <li class="list-group-item">comment->user <br> {{ $comment->user }}</li><br>
        </ul>
        <br>

        Favoriteモデル
        <ul class="list-group">
            <li class="list-group-item">favorite->article <br> {{ $favorite->article }}</li><br>
            <li class="list-group-item">favorite->user <br> {{ $favorite->user }}</li><br>
        </ul>
        <br>
    </div>
</div>