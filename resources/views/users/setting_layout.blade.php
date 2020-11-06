@extends('layouts.user.app')

@section('content')
<style>

</style>

<div class="container d-flex">
    <div class="side-menu col-md-3">
        <div class="side-menu-content">
            <h3>設定</h3>
            <div class="side-menu-item">
                <ul>
                    <li><a href="#">プロフィール設定</a></li>
                    <li><a href="#">ブログ管理</a></li>
                    <li><a href="#">記事管理</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="main-content col-md-9">
        @yield('content')
    </div>
</div>
@endsection
