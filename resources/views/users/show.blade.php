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

            <div class="profile-title-wrapper pb-5 mb-3" style="border-bottom: 1px solid #AAAAAA">
                <div class="row">
                    <div class="title col-md-12 row align-items-end mb-4">
                        <div class="col-md-10">
                            <h2>{{ $user->blog->title }}</h2>
                        </div>
                        <div class="col-md-2 text-right">
                            @if(Auth::guard('user')->user()->canFollow($user->id))
                                <form class="mb-0" action="{{ route( 'users.follows.store', ['user' => $user->id]) }}" method="POST">
                                    @csrf

                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-outline-primary form-control">フォローする</button>
                                    </div>
                                </form>
                            @else
                                <form class="mb-0" action="{{ route( 'users.follows.destroy', ['user' => $user->id, 'follow' => 0]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-primary form-control">フォロー解除</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="profile col-md-12 d-flex">
                        <div class="mr-4">
                            <img src="{{ asset('images/icon/'.$user->icon) }}" alt="user_icon" style="width:120px;">
                        </div>
                        <div style="flex-grow:1;">
                            <h3><strong>{{ $user->name }}</strong></h3>
                            <p>{{ $user->blog->overview }}</p>
                            <div class="col-md-12 row">
                                <span class="col-md-2 pr-0 pl-0">{{ count($user->follows) }}フォロー</span>
                                <span class="col-md-2 pr-0 pl-0">{{ count($user->followers) }}フォロワー</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="search-dropdown-wrapper mr-3 ml-3">
                <div class="row">
                    <div class="col-md-12 dropdown">
                        <a id="articleIndexMenuLink" class="dropdown-toggle text-dark text-right" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display:block;">
                            <h3>{{$type == 'newest' ? '新着記事' : '人気記事'}}　<i class="fas fa-chevron-down text-secondary"></i></h3>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="articleIndexMenuLink">

                            <a class="dropdown-item" href="{{ route('users.show', ['user' => $user->id]) }}?type=newest">新着記事</a>
                            <a class="dropdown-item" href="{{ route('users.show', ['user' => $user->id]) }}?type=popularity">人気記事</a>

                            <!--<form id="logout-form" action="{{ route('users.show', ['user' => 0]) }}" method="GET" style="display: none;">
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
                                        @include('components.user', ['user' => $article->blog->user, 'sub_info' => $article->updated_at])
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
