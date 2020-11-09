@extends('layouts.user.with_sidemenu')

@section('content-with-sidemenu')
    <div class="container">
        <h2 class="mb-5 font-weight-bold">記事管理</h2>
        
        <div>
            @foreach($articles as $article)
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-1">
                                        @status(['color' => $article->status->color]){{ $article->status->name }}@endstatus
                                    </div>
                                    <h3 class="col-md-11">{{ $article->title }}</h3>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection