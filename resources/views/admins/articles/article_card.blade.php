<div class="card article-card">

    <div class="card-body">

        <a class="text-secondary" href="{{ route('users.blogs.articles.show', ['user' => $article->blog->user->id, 'blog' => $article->blog_id, 'article' => $article->id]) }}">
            <p class="overflow-hidden" style="height:6em;">{{ $article->body }}</p>
            <h4 class="font-weight-bold mt-4 overflow-hidden">{{ $article->title }}</h4>
        </a>

        <div class="row mt-4">

            <div class="col-md-3">
                <a href="{{ route('admins.users.show', ['user' => $article->blog->user->id]) }}">
                    <img src="{{ asset('images/icon/'.$article->blog->user->icon) }}" alt="icon" style="
                        height:80px;
                        border-radius:50%;
                        border:1px solid rgba(0, 0, 0, 0.125);
                        background-color:white;
                    ">
                </a>
            </div>

            <div class="col-md-9 row">
                <p class="col-md-12">{{ $article->blog->user->name }}</p>
                <p class="col-md-12">{{ $article->updated_at }} 更新</p>
            </div>

        </div>
    </div>
</div>