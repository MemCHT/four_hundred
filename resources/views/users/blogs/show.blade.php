@extends('layouts.user.with_sidemenu')

<style>
    #app .user-articles-management .card{
        padding: 20px;
    }

    #app .user-articles-management .card .card-body .badge{
        height: 100%;
        font-size: 1em;
    }

    #app .user-articles-management .card .card-body .btn-group{
        margin: auto 0;
    }

    #app .user-articles-management .card .card-body .btn-group a, #app .user-articles-management .card .card-body .btn-group form{
        flex: none;
        margin: 0.9em 0;
    }

    #app .user-articles-management .card .card-body .icon-group .icon{
        flex: 1 0 auto;
    }
    #app .user-articles-management .card .card-body .icon-group .offset{
        flex: 25 0 auto;
    }
</style>

@section('content-with-sidemenu')
    <div class="container user-articles-management">
        <h2 class="mb-5 font-weight-bold">記事管理</h2>

        <div>

            @foreach($articles as $article)
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-9">
                                <div class="row mb-3">
                                    <div class="col-md-1 mr-3">
                                        @status(['color' => $article->status->color]){{ $article->status->name }}@endstatus
                                    </div>
                                    <h3 class="col-md-10 mb-0 d-flex align-items-center">{{ $article->title }}</h3>
                                </div>
                                <p>{{ $article->body }}</p>
                                <div class="row">
                                    <div class="col-md-6 d-flex align-items-center icon-group">
                                        @favorite {{ count($article->validFavorites()) }} @endfavorite
                                        @comment {{ /*count($article->validComments())*/ count($article->comments) }} @endcomment
                                        <div class="offset"></div>
                                    </div>
                                    <div class="col-md-6 text-right text-secondary">
                                        <p>{{ $article->updated_at }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 text-center d-flex flex-column btn-group">
                                <a href="{{ route('users.blogs.articles.edit', ['user' => $article->blog->user->id, 'blog' => $article->blog_id, 'article' => $article->id]) }}"
                                    class="btn btn-outline-primary">編集する</a>
                                <a href="{{ route('users.blogs.articles.comments.index', ['user' => $article->blog->user->id, 'blog' => $article->blog_id, 'article' => $article->id]) }}"
                                    class="btn btn-outline-primary">コメント管理</a>
                                <!--<a href="#" class="btn btn-outline-danger">削除する</a>-->

                                <?php $target_ids[] = 'deleteArticleBtn_'.$article->id ?>

                                <form id={{ 'deleteArticleForm_'.$article->id }} action="{{ route('users.blogs.articles.destroy', ['user' => $article->blog->user->id, 'blog' => $article->blog_id, 'article' => $article->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="form-group mb-0">
                                        <button id={{ $target_ids[count($target_ids) - 1] }} type="submit" name="article_id" value={{ $article->id }} class="btn btn-outline-danger input-group text-center d-block"
                                            onclick="{{ 'deleteArticleForm_'.$article->id }}.submit()">削除する</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <div class="text-center mb-5">
            <a href="{{ route('users.blogs.articles.create', ['user' => $user->id, 'blog' => $blog->id]) }}"
                class="btn btn-primary col-md-2">登録する</a>
        </div>
        <div>
            {{ $articles->links('vendor.pagination.modified') }}
        </div>

    </div>
@endsection

@include('components.submit_popup_contain_js',[
        'form_id' => 'deleteArticleForm',
        'target_ids' => $target_ids,
        'message' => '本当に操作を行いますか？',
        'accept' => 'はい',
        'reject' => 'いいえ'
])
