@extends(Auth::guard('admin')->check() ? 'layouts.admin.app' : 'layouts.user.app')

<style>
    .search-dropdown-wrapper .dropdown-toggle::after {
        display: none;
    }
</style>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="search-dropdown-wrapper mt-5 mr-3 ml-3">
                <div class="row">
                    <div class="col-md-12 dropdown">
                        <a id="articleIndexMenuLink" class="dropdown-toggle text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display:block;">
                            <h2>{{ $type=='newest' || $type==null ? '新着記事' : '人気記事'}}　<i class="fas fa-chevron-down text-secondary"></i></h2>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="articleIndexMenuLink">

                            <a class="dropdown-item" href="{{ route('users.header.search')."?type=newest" }}">新着記事</a>
                            <a class="dropdown-item" href="{{ route('users.header.search')."?type=popularity" }}">人気記事</a>

                        </div>
                    </div>
                </div>
            </div>

            <article-index :sort="'{{$type ?? 'newest'}}'" :keyword="'{{ $input ? http_build_query($input) : '' }}'"></article-index>
        </div>
    </div>
</div>
@endsection
