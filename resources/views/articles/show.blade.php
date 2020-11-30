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
            <a href="{{ route('users.show', ['user' => $article->blog->user->id]) }}"
                class="mb-4" style="display:block">< @include('components.text_substring', ['text' => $article->blog->title, 'length' => 100])</a>

            <div class="article-show-wrapper pb-3 mb-5" style="border-bottom: 1px solid #AAAAAA;">
                <h2 class="mb-3">{{ $article->title }}</h2>
                <div class="row mb-3">
                    <div class="col-md-6">
                        @include('components.user', ['user' => $article->blog->user, 'sub_info' => $article->updated_at])
                    </div>
                    <div class="col-md-6 d-flex flex-row-reverse">
                        <div class="ml-2">
                            @comment{{ count($article->comments) }}@endcomment
                        </div>
                        <div>
                            @favorite(['article' => $article, 'canSubmit' => true]){{ count($article->favorites) }}@endfavorite
                        </div>
                    </div>
                </div>
                <p class="mb-5" style="height:20em;">{{ $article->body }}</p>

                <div class="d-flex">

                    @if($prev = $article->getPrev())
                        <a href="{{ route('users.blogs.articles.show', ['user' => $article->blog->user_id, 'blog' => $article->blog_id, 'article' => $prev->id]) }}"
                            class="btn btn-outline-primary pr-0 pl-0" style="flex-grow:1;">< @include('components.text_substring', ['text' => $prev->title, 'length' => 10])</a>
                    @else
                        <a class="btn btn-outline-primary pr-0 pl-0 disabled" style="flex-grow:1;">< 前記事無し</a>
                    @endif

                    <div style="flex-grow:1;"></div>

                    @if($next = $article->getNext())
                        <a href="{{ route('users.blogs.articles.show', ['user' => $article->blog->user_id, 'blog' => $article->blog_id, 'article' => $next->id]) }}"
                            class="btn btn-outline-primary pr-0 pl-0" style="flex-grow:1;">@include('components.text_substring', ['text' => $next->title, 'length' => 10]) ></a>
                    @else
                        <a class="btn btn-outline-primary pr-0 pl-0 disabled" style="flex-grow:1;">次記事無し ></a>
                    @endif
                </div>
            </div>

            <div class="article-nav-wrapper text-center pb-5 mb-5" style="border-bottom: 1px solid #AAAAAA;">
                <h3 class="text-primary mb-4">{{ $article->blog->user->name }}さんの人気記事</h3>

                <!-- 後ほど人気記事をソートして代入する必要あり -->
                @if($article->blog->getArticles(1)->isEmpty() === false)
                    <div class="row mr-0 ml-0">
                        @foreach($article->blog->buildArticlesPopularity()->limit(3)->get() as $nav_article)
                            <div class="col-md-4 pr-2 pl-2">
                                <a href="{{ route('users.blogs.articles.show', ['user' => $nav_article->blog->user->id, 'blog' => $nav_article->blog_id, 'article' => $nav_article->id]) }}"
                                    class="card card-body text-dark text-left p-5">

                                    <h4 class="mb-3">{{ strlen($nav_article->title) > 15 ? substr($nav_article->title,0 , 15).'...' : $nav_article->title }}</h4>
                                    <p>{{ strlen($nav_article->body) > 75 ? substr($nav_article->body,0 , 75).'...' : $nav_article->body }}</p>
                                    <div class="row">
                                        <div class="col-md-6 d-flex">
                                            <div>
                                                @favorite(['article' => $nav_article, 'canSubmit' => false]){{ count($nav_article->favorites) }}@endfavorite
                                            </div>
                                            <div class="ml-2">
                                                @comment{{ count($nav_article->comments) }}@endcomment
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-right text-secondary">{{ $nav_article->updated_at->format('Y/m/d') }}</div>
                                    </div>
                                </a>
                            </div>

                        @endforeach
                    </div>
                @else
                    <h4 class="text-secondary pr-2 pl-2">公開中の記事がありません</h4>
                @endif
            </div>

            <div class="article-comments-wrapper">
                <div class="col-md-11 row justify-content-center mx-auto">
                    <div class="text-center text-primary mb-3" style="font-size: 1.25em;">
                        @comment コメント{{ count($article->comments) }}件@endcomment
                    </div>
                    @foreach($article->comments as $comment)
                        <div class="pb-5 mb-4 col-md-12 pr-0 pl-0" style="border-bottom: 1px solid #AAAAAA">
                            <div class="mb-3">
                                @include('components.user', ['user' => $comment->user, 'sub_info' => $comment->updated_at])
                            </div>
                            <p>{{ $comment->body }}</p>
                        </div>
                    @endforeach
                    <div class="col-md-12 pr-0 pl-0 text-center">
                        <form id="commentForm" action="{{ route('users.blogs.articles.comments.store', ['user'=>$article->blog->user_id, 'blog'=>$article->blog_id, 'article'=>$article->id]) }}"
                            class="form-group" method="POST"{{ old('body') ?:'hidden' }}>
                            @csrf

                            <textarea id="commentBody" type="text" class="form-control mb-4" name="body" rows="5" placeholder="コメントを入力" required>{{ old('body') }}</textarea>
                        </form>
                        @include('components.error', ['name' => 'body'])

                        <button id="commentFormBtn" type="submit" class="btn btn-primary mb-5" form="commentForm">コメントを投稿する</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        const commentForm = document.getElementById('commentForm');
        const commentFormBtn = document.getElementById('commentFormBtn');
        const CommentBody = document.getElementById('commentBody');

        if(commentForm.hidden){
            commentFormBtn.addEventListener('click', (event)=>{
                event.preventDefault();

                commentForm.hidden = false;

                commentFormBtn.addEventListener('click', (event)=>{
                    commentForm.submit();
                });
            });
        }else{
            commentBody.focus();
        }
    });

    /*const handleClick = () => {
        const commentForm = document.getElementById('commentForm');
        const commentFormBtn = document.getElementById('commentFormBtn');

        return (event: MouseEvent) => {
            event.preventDefault();

            commentForm.hidden = false;

            commentFormBtn.addEventListener('click', (event)=>{
                commentForm.submit();
            });
        }
    }*/
</script>
