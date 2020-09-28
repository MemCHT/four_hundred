@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="title-wrapper mt-5">
                <h3>エッセイの投稿</h3>

                <form action=" {{route('users.blogs.store',['user' => $user->id, 'blog' => $user->blog_id])}} " method="POST">
                    @csrf

                    <div class="form-group col-md-4">
                        <div>
                            <label for="" >ステータス</label>
                        </div>

                        <select class="form-control" name="status_id">
                            @foreach($statuses as $status)
                                @if(old('status_id'))
                                <option value="{{ $status->id }}" {{ $status->id == old('status_id') ? 'selected' : '' }}>{{ $status->name }}</option>
                                @else
                                <option value="{{ $status->id }}" {{ $status->name == '下書き' ? 'selected' : '' }}>{{ $status->name }}</option>
                                @endif

                            @endforeach
                        </select>

                        @component('components.error',['name' => 'status_id']) @endcomponent
                    </div>

                    <div class="form-group col-md-12">
                        <label for="">タイトル（40文字まで）</label>
                        <input type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="エッセイのタイトルを記入">

                        @component('components.error',['name' => 'title']) @endcomponent
                    </div>

                    <div class="form-group col-md-12">
                        <label for="">本文（390文字から400文字）</label>
                        <textarea class="form-control" name="body" rows="3" placeholder="本文を記入">{{ old('body') }}</textarea>

                        @component('components.error',['name' => 'body']) @endcomponent

                    </div>
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-outline-secondary">確定</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection