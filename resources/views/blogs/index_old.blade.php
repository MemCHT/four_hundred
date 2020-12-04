@extends(Auth::guard('admin')->check() ? 'layouts.admin.app' : 'layouts.user.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="title-wrapper mt-5">
                <div class="row">
                    <div class="col-md-12">
                        <h2> ブログ一覧 </h2>
                    </div>
                </div>
            </div>

            <div class="card-wrapper mt-5">

                @foreach($blogs as $blog)
                <!-- 公開記事のみ閲覧可能 -->
                @if( $blog->status->name === '公開')
                <div class="card card-default mb-3">
                    <div class="card-header">
                        <div class="row">
                            <h3 class="col-md-10">{{ strlen($blog->title) > 40 ? substr($blog->title,0 , 40).'...' : $blog->title }}</h3>
                            <div class="col-md-2 text-right">
                                @favorite
                                {{ $blog->favorites_count }}
                                @endfavorite

                                @article
                                {{ $blog->articles_count }}
                                @endarticle
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- 記事のupdated_atに対応させる必要あり -->
                            @if($blog->latest_article)
                            <h4 class="col-md-8">{{ strlen($blog->latest_article['title']) > 40 ? substr($blog->latest_article['title'],0 , 200).'...' : $blog->latest_article['title'] }}</h4>
                            <div class="col-md-4 text-right">
                                @status(['color' => $blog->status->color])
                                {{ $blog->status->name }}
                                @endstatus
                            </div>
                            <div class="col-md-12">{{$blog->latest_article['updated_at']}}</div>
                            <div class="col-md-12">
                                <p>{{ strlen($blog->latest_article['body']) > 200 ? substr($blog->latest_article['body'],0 , 200).'...' : $blog->latest_article['body'] }}</p>
                            </div>
                            @else
                            <h4 class="col-md-8">未投稿</h4>
                            <div class="col-md-4 text-right">
                                @status(['color' => $blog->status->color])
                                {{ $blog->status->name }}
                                @endstatus
                            </div>
                            @endif
                        </div>
                        <a class="btn btn-primary" href="{{ route('users.blogs.show', ['user' => $blog->user_id, 'blog' => $blog->id]) }}">ブログ詳細</a>
                    </div>
                </div>
                @endif
                @endforeach
            </div>

            {{$blogs->links('vendor.pagination.modified')}}
        </div>
    </div>
</div>
@endsection