
<!-- favorite - endfavorite エイリアスで呼び出し化可 -->
<!-- 引数 ['slot' => xx(お気に入り数), 'article' => xx(Articleインスタンス/省略可), 'favorite' => xx(Favoriteインスタンス/省略可)] -->


<!-- articleを渡さなければ、ただのアイコンとして使える -->
@if(isset($article) == false)
    <div class="component-favorite icon">
        <i class="fas fa-heart text-danger"></i><span class="text-danger"> {{ $slot }}</span>
    </div>

@elseif(isset($article) && isset($canSubmit) && $canSubmit === false)
<?php $favorite = $article->getFavorite(Auth::guard('user')->user()->id) ?>

<div class="component-favorite icon">
        <!-- favoriteのステータスによってアイコンを変える TODO_元々表示に使っていた画面のエラー解消！ -->
        <i class="{{ isset($favorite) && $favorite->status ? 'fas' : 'far' }} fa-heart text-danger"></i><span> {{ $article->getFavoritesCount() }}</span>
</div>

@else
<?php $favorite = $article->getFavorite(Auth::guard('user')->user()->id) ?>

    <div class="component-favorite icon" style="cursor: pointer;">
        <a onclick="event.preventDefault();
                    document.getElementById('favorite-form').submit()">

            <!-- favoriteのステータスによってアイコンを変える -->
            <i class="{{ isset($favorite) && $favorite->status ? 'fas' : 'far' }} fa-heart text-danger"></i><span> {{ $article->getFavoritesCount() }}</span>
        </a>
    </div>

    <!-- コンポネント使用時に$favoriteがセットされているかどうか -->
    <!-- セットされていない → favoriteが登録されていない。 -->

    @auth
    @if(isset($favorite))
    <!-- favoriteのupdate処理 -->
    <form id="favorite-form" action="{{ route('users.blogs.articles.favorites.update', ['user' => $article->blog->user_id, 'blog' => $article->blog_id, 'article' => $article->id, 'favorite' => $favorite->id]) }}" method="POST" hidden>
        @method('PUT')
        @csrf
    </form>

    @else
    <!-- favoriteのcreate処理 -->
    <form id="favorite-form" action="{{ route('users.blogs.articles.favorites.store', ['user' => $article->blog->user_id, 'blog' => $article->blog_id, 'article' => $article->id]) }}" method="POST" hidden>
        @csrf
    </form>
    @endif

    @endauth
    @guest
        <form id="favorite-form" action="{{ route('users.login') }}" method="GET" hidden></form>
    @endguest
@endif
