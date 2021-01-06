@extends(Auth::guard('admin')->check() ? 'layouts.admin.app' : 'layouts.user.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <!--<div class="title-wrapper mt-5">
                <div class="row">
                    <div class="col-md-12">
                        <h2> ブログ一覧 </h2>
                    </div>
                </div>
            </div>-->

            <!-- TODO_2021/01/04 タグが登録されていない判定される問題の解決 -->
            <blog-index></blog-index>

            <!--<div class="text-center mb-4">
                <button class="btn btn-primary col-md-4">もっと見る</button>
            </div>-->
            {{$blogs->appends($input)->links('vendor.pagination.modified')}}
        </div>
    </div>
</div>
@endsection

<script src="{{mix('js/app.js')}}"></script>
