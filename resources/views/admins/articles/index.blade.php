@extends('layouts.admin.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <h2 class="col-md-4 font-weight-bold">記事一覧</h2>

        <div class="col-md-8">
            <form class="d-flex">
                <div style="flex:1;">
                    <div class="pr-2">@include('components.search', [
                        'name' => 'blogTitle',
                        'placeholder' => 'ブログ名で検索',
                        'value' => request()->input('blogTitle') ?? ''
                    ])</div>
                </div>
                <div style="flex:1;">
                    <div class="pl-2">@include('components.search', [
                        'name' => 'title',
                        'placeholder' => '記事タイトルで検索',
                        'value' => request()->input('title') ?? ''
                    ])</div>
                </div>
            </form>
        </div>

        <div class="col-md-12 row pr-0 pl-0 mt-5">
            @foreach($articles as $article)
                <div class="col-md-6 mb-4">
                    @include('admins.articles.article_card', ['article' => $article])
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $articles->appends(request()->input())->links('vendor.pagination.modified') }}
        </div>
    </div>
</div>
@endsection
