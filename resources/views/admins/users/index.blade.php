@extends('layouts.admin.app')

@section('content')
<div class="container">
    <div class="row justify-content-center text-secondary">
        <h2 class="col-md-4 offset-md-4 text-center font-weight-bold">ユーザー一覧</h2>
        <div class="col-md-4">
            @component('components.search', ['route' => route('admins.users.index')]) @endcomponent
        </div>
        @if(session()->has('keyword'))
            <h3 class="col-md-12 text-center">検索ワード:「{{ session()->get('keyword') }}」</h3>
        @endif
        <div class="col-md-12 row">
            @foreach($users as $user)
                <div class="col-md-4 mt-3">
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