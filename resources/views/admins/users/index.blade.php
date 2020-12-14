@extends('layouts.admin.app')
<!-- 2020/11/30 TODO_レイアウト整える -->
@section('content')
<div class="container">
    <div class="row justify-content-center">

        <h2 class="col-md-4 font-weight-bold">ユーザー管理</h2>{{ old('name') ? dd(old('name')) : '' }}

        <div class="col-md-8">
            <form class="d-flex">
                <div style="flex:1;">
                    <div class="pr-2">@include('components.search', [
                        'name' => 'name',
                        'placeholder' => 'ユーザー名で検索',
                        'value' => request()->input('name') ?? ''
                    ])</div>
                </div>
                <div style="flex:1;">
                    <div class="pl-2">@include('components.search', [
                        'name' => 'email',
                        'placeholder' => 'メールアドレスで検索',
                        'value' => request()->input('email') ?? ''
                    ])</div>
                </div>
            </form>
        </div>

        <div class="col-md-12 row pr-0 pl-0 mt-5">
            @foreach($users as $user)

                <?php
                    $user_freeze_btn_id = 'userFreezeBtn_'.$user->id;
                    $user_freeze_btn_ids[] = $user_freeze_btn_id;
                ?>

                <div class="col-md-6 mb-4">
                    @include('admins.users.user_card', ['user' => $user, 'target_id' => $user_freeze_btn_id])
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $users->appends(request()->input())->links('vendor.pagination.modified') }}
        </div>

    </div>
</div>
@endsection

@include('components.submit_popup_contain_js',[
        'form_id' => 'userFreezeForm',
        'target_ids' => $user_freeze_btn_ids,
        'message' => 'アカウントを停止しますか？',
        'accept' => '停止する',
        'reject' => 'キャンセル'
    ])
