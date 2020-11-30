@extends('layouts.admin.app')

@section('content')
<div class="container">
    <div class="row justify-content-center text-secondary">

        <h2 class="col-md-4 offset-md-4 text-center font-weight-bold">エッセイ一覧</h2>

        <div class="col-md-4">
            @component('components.search', ['route' => route('admins.articles.index'), 'placeholder' => 'タイトル/本文で検索'])
                <div class="input-group">

                    <select class="form-control" name="status_id">
                        <option value="all">ステータスで絞り込み</option>

                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" @if(session()->get('status_id') == $status->id) selected @endif>{{ $status->name }}</option>
                        @endforeach
                    </select>

                </div>
            @endcomponent
        </div>

        @if(session()->has('keyword'))
            <h3 class="col-md-12 text-center">検索ワード「{{ session()->get('keyword') }}」</h3>
        @endif

        @if(session()->has('status_id'))
            <h3 class="col-md-12 text-center">ステータス:「{{ $current_status->name }}」</h3>
        @endif

        <div class="col-md-12 row">
            @foreach($articles as $article)
                <div class="col-md-6 mt-3">
                    @include('admins.articles.old_article_card', ['article' => $article])
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $articles->links('vendor.pagination.modified') }}
        </div>
    </div>
</div>
@endsection
