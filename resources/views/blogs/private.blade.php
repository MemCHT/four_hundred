@extends(Auth::guard('admin')->check() ? 'layouts.admin.app' : 'layouts.user.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="title-wrapper mt-5">
                <h2>
                    非公開のブログです。
                </h2>
            </div>
        </div>
    </div>
</div>
@endsection