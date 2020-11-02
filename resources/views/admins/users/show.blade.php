@extends('layouts.admin.app')

@section('content')
<div class="container">
    <div class="row justify-content-center text-secondary">
        <h2 class="text-center font-weight-bold col-md-12 mt-4">ユーザー詳細</h2>
        <div class="col-md-10 card user-show mt-3">
            <div class="card-body row">

                <div class="col-md-4">
                    <img src="{{ asset('images/icon/'.$user->icon) }}" alt="icon"
                    style="
                        height: 12em;
                        border-radius: 50%;
                        border: 1px solid rgba(0, 0, 0, 0.125);
                        background-color: white;
                    ">
                </div>

                <div class="col-md-8">

                    <div class="row mt-3 d-flex align-items-center">
                        <h5 class="col-md-4 font-weight-bold">ユーザー名</h5>
                        <p class="col-md-8">{{ $user->name }}</p>
                    </div>

                    <div class="row mt-3 d-flex align-items-center">
                        <h5 class="col-md-4 font-weight-bold">メールアドレス</h5>
                        <p class="col-md-8">{{ $user->email }}</p>
                    </div>

                    <div class="row mt-3 d-flex align-items-center">
                        <h5 class="col-md-4 font-weight-bold">ステータス</h5>
                        <div class="col-md-4">@status(['color' => $user->status->color]){{ $user->status->name }}@endstatus</div>
                        <div class="col-md-4">
                            <form action="{{ route('admins.users.update', ['user' => $user->id]) }}" method="POST">
                                @csrf
                                @method('PUT')

                                @if($user->status->isPublic())
                                    <input type="number" name="status_id" value={{ $statuses->lock_id }} hidden>
                                    <button type="submit" class="btn btn-{{ 'danger' }} require_confirm" onclick="event.preventDefault();confirmAlert(event);">凍結する</button>
                                @else
                                    <input type="number" name="status_id" value={{ $statuses->unlock_id }} hidden>
                                    <button type="submit" class="btn btn-{{ 'success' }} require_confirm" onclick="event.preventDefault();confirmAlert(event);">解除する</button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card col-md-12 mt-3">
                    <div class="card-body text-center">
                        <h3 class="font-weight-bold">メール送信フォーム</h3>
                        <form action="{{ route('admins.users.sendmail', ['user' => $user->id]) }}" method="POST">
                            @csrf

                            @include('components.error', ['name' => 'body'])
                            <textarea class="form-control col-md-12" name="body" cols="30" rows="10">@php
                                echo("$user->name 様\n");
                                echo("\n");
                                echo("こんにちは！いつもfour_hundredアプリをご利用いただきありがとうございます！\n");
                                echo("\n");
                                echo("[body] \n");
                                echo("\n");
                                echo("hoge社 執筆企画チーム\n");
                                echo(Auth::guard('admin')->user()->name."より");
                            @endphp</textarea>

                            <button type="submit" class="btn btn-secondary mt-3" onclick="event.preventDefault();confirmAlert(event);">送信する</button>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function confirmAlert(e){
        if(!window.confirm('本当に操作を行いますか？')){
            window.alert('キャンセルされました');
            return false;
        }

        let target = e.target.parentNode;
        target.submit();
    }
</script>