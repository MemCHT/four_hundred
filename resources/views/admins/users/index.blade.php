@extends('layouts.admin.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h2 class="text-center text-secondary font-weight-bold">ユーザー一覧</h2>
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