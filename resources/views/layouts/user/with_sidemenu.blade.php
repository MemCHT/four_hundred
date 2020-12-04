@extends('layouts.user.app')

@section('sidemenu')

<div class="sidemenu-bg">

</div>
<div class="d-flex with-sidemenu">
    <div class="sidemenu">
        <div class="container">
            <h3 class="mb-5 font-weight-bold">設定</h3>
            <div class="sidemenu-item">
                <ul class="list-group">
                    <li class="list-group-item"><a href="{{ route("users.profile.edit") }}">プロフィール設定</a></li>
                    <li class="list-group-item"><a href="{{ route('users.blogs.edit', ['user' => Auth::guard('user')->user()->id, 'blog' => Auth::guard('user')->user()->blog->id]) }}">ブログ管理</a></li>
                    <li class="list-group-item"><a href="{{ route('users.blogs.show', ['user' => Auth::guard('user')->user()->id, 'blog' => Auth::guard('user')->user()->blog->id]) }}">記事管理</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="main-content">
        @yield('content-with-sidemenu')
        @include('layouts.user.footer')
    </div>
</div>
@endsection

