@extends('layouts.admin.app')

@section('content')
<div class="container">
    <div class="row justify-content-center text-secondary">
        <h2 class="text-center font-weight-bold">エッセイ一覧</h2>
        <div class="col-md-12 row">
            @foreach($articles as $article)
                <div class="col-md-6 mt-3">
                    @include('admins.articles.article_card', ['article' => $article])
                </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $articles->links('vendor.pagination.modified') }}
        </div>
    </div>
</div>
@endsection