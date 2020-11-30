<div class="card article-card p-4">
    <div class="card-body">
        <h3 class="mb-3">@include('components.text_substring',['text' => $article->title, 'length' => 25])</h3>
        <small>{{ $article->blog->title }}</small>

        <p>@include('components.text_substring', ['text' => $article->body, 'length' => 80])</p>

        <div class="d-flex">
            <div class="text-primary" style="flex:1;">
                <button class="btn btn-outline-primary col-md-11">記事詳細</button>
            </div>
            <div class="text-danger text-right" style="flex:1;">
                <button class="btn btn-outline-danger col-md-11">削除する</button>
            </div>
        </div>
    </div>
</div>
