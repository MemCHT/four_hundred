<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://kit.fontawesome.com/652b493660.js" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">

    <style>

    </style>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md bg-white shadow-sm pb-0 pt-4 mb-5">
            <div class="container flex-column">
                <div class="nav-first col-md-12 d-flex">
                    <a class="navbar-brand" href="{{ route('admins.home')}}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto align-items-center">
                            <!-- Authentication Links -->
                            @auth('admin')

                                <li class="nav-item mr-5">
                                    <span class="notification-icon">
                                        <i class="far fa-bell"></i>
                                    </span>
                                </li>

                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::guard('admin')->user()->name }}
                                        <span class="caret ml-5">
                                            <img src="{{ asset('images/icon/admin.png') }}" alt="admin_icon">
                                        </span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('admins.logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('layouts.logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('admins.logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @else
                            @endauth
                        </ul>
                    </div>
                </div>

                <!-- sidemenuから派生したコンポーネントは、以下を表示しない -->
                @if(Auth::guard('admin')->check() && View::hasSection('content'))
                    <div class="nav-second col-md-12 d-flex mt-3">
                        <div class="collapse navbar-collapse">
                            <ul class="navbar-nav ml-auto mr-auto">
                                <li class="nav-item"><a class="nav-link" href="{{ route('admins.users.index') }}">ユーザー管理</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admins.articles.index') }}">記事管理</a></li>
                                <li class="nav-item"><a class="nav-link" href="#">コメント一覧</a></li>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </nav>

        <main class="pt-4">
            @yield('sidemenu')
            @yield('content')
        </main>
    </div>

    @include('layouts.success')
</body>
</html>


