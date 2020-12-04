@extends('layouts.user.app')

<style>
    #app nav{
        z-index: 1000;
    }

    main .sidemenu-bg{
        position: absolute;
        background-color: #F4F8FA;
        top: 0;
        left: 0;
        width:20%;
        height: 100%;
        z-index: 0;
    }

    main .with-sidemenu .sidemenu{
        z-index: 1;
        flex: 1 0 20%;
    }
    main .with-sidemenu .sidemenu .container{
        padding-left: 3em;
        padding-right: 0;
    }
    main .with-sidemenu .sidemenu .container .sidemenu-item .list-group .list-group-item{
        border:0 solid white;
        padding:0;
        background-color: transparent;
        transition: background-color 0.3s ease;
    }
    main .with-sidemenu .sidemenu .container .sidemenu-item .list-group .list-group-item:hover{
        background-color: white;
    }
    main .with-sidemenu .sidemenu .container .sidemenu-item .list-group .list-group-item a{
        display:block;
        padding:1.5em 1em;
        color: #333333;
    }
    main .with-sidemenu .sidemenu .container .sidemenu-item .list-group .list-group-item a:hover{
        text-decoration: none;
    }

    main .with-sidemenu .main-content{
        flex: 4 0 80%;
    }
</style>

@section('sidemenu')

<div class="sidemenu-bg">

</div>
<div class="d-flex with-sidemenu">
    <div class="sidemenu">
        <div class="container">
            <h3 class="mb-5">設定</h3>
            <div class="sidemenu-item">
                <ul class="list-group">
                    <li class="list-group-item"><a href="#" style="text-decoration:none;">プロフィール設定</a></li>
                    <li class="list-group-item"><a href="#">ブログ管理</a></li>
                    <li class="list-group-item"><a href="#">記事管理</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="main-content">
        @yield('content-with-sidemenu')
    </div>
</div>
@endsection
