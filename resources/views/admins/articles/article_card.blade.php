<div class="card article-card p-4">
    <div class="card-body">
        <div class="mb-3">
            <h3 class="mb-0">@include('components.text_substring',['text' => $article->title, 'length' => 25])</h3>
            <small>@include('components.text_substring', ['text' => $article->blog->title, 'length' => 50])</small>
        </div>

        <p style="height:5em;">@include('components.text_substring', ['text' => $article->body, 'length' => 150])</p>

        <div class="d-flex">
            <div class="text-primary" style="flex:1;">
                <a href="{{ route('users.blogs.articles.show', ['user' => $article->blog->user_id, 'blog' => $article->blog_id, 'article' => $article->id]) }}" class="btn btn-outline-primary col-md-11">記事詳細</a>
            </div>
            <div class="text-danger text-right" style="flex:1;">
                <form action="{{ route('admins.articles.destroy', ['article' => $article->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-outline-danger col-md-11">削除する</button>
                </form>
            </div>
        </div>
    </div>
</div>
