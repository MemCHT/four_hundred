
<!-- favorite - endfavorite エイリアスで呼び出し化可 -->
<!-- 引数 ['slot' => xx(お気に入り数), 'article' => xx(Articleインスタンス/省略可), 'favorite' => xx(Favoriteインスタンス/省略可)] -->


<!-- articleを渡さなければ、ただのアイコンとして使える -->
@if(isset($article) == false)
<div class="component-favorite">
    <i class="far fa-star text-info"></i><span>{{ $slot }}</span>
</div>

@else

<a class="component-favorite btn btn-secondary" onclick="event.preventDefault();
                                                    document.getElementById('favorite-form').submit()">

    <!-- favoriteのステータスによってアイコンを変える -->
    <i class="{{ isset($favorite) && $favorite->status ? 'fas' : 'far' }} fa-star text-info"></i><span>{{ $slot }}</span>
</a>

    <!-- コンポネント使用時に$favoriteがセットされているかどうか -->
    <!-- セットされていない → favoriteが登録されていない。 -->

    @if(isset($favorite))
    <!-- favoriteのupdate処理 -->
    <form id="favorite-form" action="{{ route('users.blogs.articles.favorites.update', ['user' => $article->blog->user_id, 'blog' => $article->blog_id, 'article' => $article->id, 'favorite' => $favorite->id]) }}" method="POST">
        @method('PUT')
        @csrf
    </form>

    @else
    <!-- favoriteのcreate処理 -->
    <form id="favorite-form" action="{{ route('users.blogs.articles.favorites.store', ['user' => $article->blog->user_id, 'blog' => $article->blog_id, 'article' => $article->id]) }}" method="POST">
        @csrf
    </form>

    @endif
@endif