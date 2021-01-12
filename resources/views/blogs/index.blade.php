@extends(Auth::guard('admin')->check() ? 'layouts.admin.app' : 'layouts.user.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <blog-index :keyword="'{{ $input ? http_build_query($input) : '' }}'"></blog-index>

        </div>
    </div>
</div>
@endsection
