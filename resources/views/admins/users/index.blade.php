@extends('layouts.admin.app')
<!-- 2020/11/30 TODO_レイアウト整える -->
@section('content')
<div class="container">
    <div class="row justify-content-center">

        <h2 class="col-md-8 font-weight-bold">ユーザー管理</h2>

        <div class="col-md-4">
            @component('components.search', ['route' => route('admins.users.index'), 'placeholder' => 'ユーザー名/emailで検索']) @endcomponent
        </div>

        @if(session()->has('keyword'))
            <h3 class="col-md-12 text-center">検索ワード:「{{ session()->get('keyword') }}」</h3>
        @endif

        <div class="col-md-12 row pr-0 pl-0">
            @foreach($users as $user)
                <div class="col-md-6 mt-3">
                    @include('admins.users.user_card', ['user' => $user])
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $users->links('vendor.pagination.modified') }}
        </div>

    </div>
</div>
@endsection
