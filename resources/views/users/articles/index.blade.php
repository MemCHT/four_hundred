@extends(Auth::guard('admin')->check() ? 'layouts.admin.app' : 'layouts.user.app')

<style>
    .search-dropdown-wrapper .dropdown-toggle::after {
        display: none;
    }
</style>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="search-dropdown-wrapper mt-5 mr-3 ml-3">
                <div class="row">
                    <div class="col-md-12 dropdown">
                        <a id="articleIndexMenuLink" class="dropdown-toggle text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display:block;">
                            <h2>{{$type=='newest' ? '新着記事' : '人気記事'}}　<i class="fas fa-chevron-down text-secondary"></i></h2>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="articleIndexMenuLink">

                            <a class="dropdown-item" href="{{ route('users.blogs.articles.index', ['user' => 0, 'blog' => 0])."?type=newest" }}">新着記事</a>
                            <a class="dropdown-item" href="{{ route('users.blogs.articles.index', ['user' => 0, 'blog' => 0])."?type=popularity" }}">人気記事</a>

                            <!--<form id="logout-form" action="{{ route('users.blogs.articles.index', ['user' => 0, 'blog' => 0]) }}" method="GET" style="display: none;">
                                @csrf
                            </form>-->
                        </div>
                    </div>
                </div>
            </div>

            <div class="article-index-wrapper mt-5">

                <div class="row mr-0 ml-0">
                    @foreach($articles as $article)
                        <div class="col-md-6 mb-4">
                            <a href="{{ route('users.blogs.articles.show', ['user' => $article->blog->user->id, 'blog' => $article->blog_id, 'article' => $article->id]) }}"
                                class="card card-body p-5" style="color: #333333;">

                                <h4 class="mb-3">
                                    <!-- これもコンポーネント / 関数にしてもよさそう。 -->
                                    {{ strlen($article->title) > 35 ? substr($article->title,0 , 35).'...' : $article->title }}
                                    <br>
                                    <small style="font-size: 0.6em; line-height:1.5em;">
                                        {{ strlen($article->blog->title) > 50 ? substr($article->blog->title,0 , 50).'...' : $article->blog->title }}
                                    </small>
                                </h4>

                                <p>{{ strlen($article->body) > 130 ? substr($article->body,0 , 130).'...' : $article->body }}</p>
                                <div class="row align-items-center">

                                    <!-- コメント一覧と同じ、ユーザー表示をコンポーネント化してもよい -->
                                    <div class="comment-header d-flex align-items-center col-md-6">
                                        <!--<img src="{{asset('images/icon/'.$article->blog->user->icon)}}" alt="comment-icon" style="height: 1.8em">

                                        <div class="ml-3">
                                            <p class="mb-0"><strong>{{$article->blog->user->name}}</strong></p>
                                            <p class="mb-0 comment-created-at"><small>{{$article->updated_at}}</small></p>
                                        </div>-->
                                        @include('components.user',['user' => $article->blog->user, 'sub_info' => $article->updated_at])
                                    </div>

                                    <div class="col-md-6 d-flex flex-row-reverse">
                                        <div class="ml-2">
                                            @comment{{ count($article->comments) }}@endcomment
                                        </div>
                                        <div>
                                            @favorite(['article' => $article, 'canSubmit' => false]){{ count($article->favorites) }}@endfavorite
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    @endforeach
                </div>

            <div class="text-center mb-4">
                <button class="btn btn-primary col-md-4">もっと見る</button>
            </div>
            {{$articles->links('vendor.pagination.modified')}}
        </div>
    </div>
</div>
@endsection
