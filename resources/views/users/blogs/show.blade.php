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

    #app .user-articles-management .card .card-body .btn-group a{
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
                                        @comment {{ count($article->validComments()) }} @endcomment
                                        <div class="offset"></div>
                                    </div>
                                    <div class="col-md-6 text-right text-secondary">
                                        <p>{{ $article->updated_at }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 text-center d-flex flex-column btn-group">
                                <a href="#" class="btn btn-outline-primary">編集する</a>
                                <a href="#" class="btn btn-outline-primary">コメント管理</a>
                                <a href="#" class="btn btn-outline-danger">削除する</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
