@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>


    <div class="mt-5">
        <form action="/upload" method="POST">
            @csrf
            <input type="file" name="file" accept="image/jpeg">
            <input type="submit">
        </form>
        {{--成功時のメッセージ--}}
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        {{-- エラーメッセージ --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>


</div>
@endsection
