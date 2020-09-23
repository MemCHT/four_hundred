@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="title-wrapper mt-5">
                <h2>{{$blog->title}}
                    <a class="btn btn-secondary" href="{{route('users.blogs.edit', ['user' => $user_id, 'blog' => $blog_id])}}"> 編集 </a>
                </h2>
            </div>


            <div class="card-wrapper mt-5">
                <h3 class="mb-3">{{$user->name}}さんのエッセイ一覧</h3>

                @foreach($articles as $article)
                <div class="card card-default mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">{{$article->title}}</div>
                            <div class="col-md-2 text-right">
                                <i class="far fa-star text-info"></i>{{count($article->favorites)}}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">{{$article->updated_at}}</div>
                            <div class="col-md-4 text-right">
                                    <i class="fas fa-circle {{$article->status->color}}"></i>{{$article->status->name}}
                            </div>
                        </div>
                        <p>{{$article->body}}</p>
                        <a class="btn btn-primary" href="#">エッセイ詳細</a>
                    </div>
                </div>
                @endforeach
            </div>

            {{$articles->links('vendor.pagination.modified')}}
        </div>
    </div>
</div>
@endsection