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

                <?php
                    $article_card_delete_btn_id = 'articleCardDeleteBtn_'.$article->id;
                    $article_card_delete_btn_ids[] = $article_card_delete_btn_id;
                ?>

                <div class="col-md-6 mb-4">
                    @include('admins.articles.article_card', ['article' => $article, 'target_id' => $article_card_delete_btn_id])
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $articles->appends(request()->input())->links('vendor.pagination.modified') }}
        </div>
    </div>
</div>
@endsection

@include('components.submit_popup_contain_js',[
        'form_id' => 'articleDeleteForm',
        'target_ids' => $article_card_delete_btn_ids,
        'message' => '記事を削除しますか？',
        'sub_message' => '記事を削除してもよろしいですか？<br>記事を削除すると、元に戻すことはできません。',
        'accept' => '削除する',
        'reject' => 'キャンセル'
    ])
