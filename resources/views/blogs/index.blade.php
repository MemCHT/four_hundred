@extends(Auth::guard('admin')->check() ? 'layouts.admin.app' : 'layouts.user.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <!--<div class="title-wrapper mt-5">
                <div class="row">
                    <div class="col-md-12">
                        <h2> ブログ一覧 </h2>
                    </div>
                </div>
            </div>-->

            <div class="blog-index-wrapper mt-5">
                @foreach($blogs as $blog)
                    <div class="pb-3 mb-5">

                        <a class="row col-md-12 text-dark m-0 pr-2 pl-2 mb-2" href="{{ route('users.show', ['user' => $blog->user_id]) }}">
                            <h2 class="col-md-10 p-0">
                                {{ strlen($blog->title) > 60 ? substr($blog->title,0 , 60).'...' : $blog->title }}
                            </h2>
                            <div class="col-md-2 d-flex flex-row-reverse align-items-center p-0">
                                <img src="{{ asset('/images/icon/'.$blog->user->icon) }}" alt="user_icon" style="height:1.8rem">
                                <p class="mb-0 mr-3">{{ $blog->user->name }}</p>
                            </div>
                        </a>

                        @if($blog->getArticles(1)->isEmpty() === false)
                            <div class="row mr-0 ml-0">
                                @foreach($blog->getArticles(3) as $article)
                                    <div class="col-md-4 pr-2 pl-2">
                                        <a href="{{ route('users.blogs.articles.show', ['user' => $article->blog->user->id, 'blog' => $article->blog_id, 'article' => $article->id]) }}"
                                            class="card card-body text-dark p-5">

                                            <h4 class="mb-3">{{ strlen($article->title) > 15 ? substr($article->title,0 , 15).'...' : $article->title }}</h4>
                                            <p>{{ strlen($article->body) > 75 ? substr($article->body,0 , 75).'...' : $article->body }}</p>
                                            <div class="row">
                                                <div class="col-md-6 d-flex">
                                                    <div>
                                                        @favorite(['article' => $article, 'canSubmit' => false]){{ count($article->favorites) }}@endfavorite
                                                    </div>
                                                    <div class="ml-2">
                                                        @comment{{ count($article->comments) }}@endcomment
                                                    </div>
                                                </div>
                                                <div class="col-md-6 text-right text-secondary">{{ $article->updated_at->format('Y/m/d') }}</div>
                                            </div>
                                        </a>
                                    </div>

                                @endforeach
                            </div>
                        @else
                            <h4 class="text-secondary pr-2 pl-2">公開中の記事がありません</h4>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="text-center mb-4">
                <button class="btn btn-primary col-md-4">もっと見る</button>
            </div>
            {{$blogs->appends($input)->links('vendor.pagination.modified')}}
        </div>
    </div>
</div>
@endsection
