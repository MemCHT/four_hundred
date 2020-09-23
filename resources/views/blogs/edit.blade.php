@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="title-wrapper mt-5">
                <h3 clas="">ブログタイトル編集</h3>

                <form action="{{ route('users.blogs.update', ['user_id' => $user_id, 'blog' => $blog_id]) }}"
                    method="POST">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{$blog->title}}" placeholder="ブログ名を記入">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">確定</button>
                        </div>
                    </div>
                </form>


                <div class="card-wrapper mt-5">
                    <h3 class="mb-3">{{$user->name}}さんのエッセイ一覧</h3>

                    @foreach($articles as $article)
                    <div class="card card-default mb-3">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-10">
                                    {{$article->title}}
                                </div>
                                <div class="col-md-2 text-right">
                                    <i class="far fa-star text-info"></i>{{count($article->favorites)}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p>{{$article->updated_at}}</p>
                            <p>{{$article->body}}</p>
                            <a class="btn btn-primary" href="#">編集</a>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{$articles->links('vendor.pagination.modified')}}
            </div>
        </div>
    </div>
</div>
@endsection