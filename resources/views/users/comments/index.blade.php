@extends('layouts.user.with_sidemenu')

<style>
    #app .comment-index-form .form-check input[type='checkbox']{
        display: none;
    }
    #app .comment-index-form .form-check input[type='checkbox'] ~ label .checkbox-icon {
        display: block;
        width: 30px;
        height: 30px;
        border: 3px solid #707070;
        border-radius: 3px;
        text-align: center;
        transition: 0.1s;
    }
    #app .comment-index-form .form-check input[type='checkbox'] ~ label .checkbox-icon i {
        font-size: 22.5px;
        color: white;
    }
    #app .comment-index-form .form-check input[type='checkbox']:checked ~ label .checkbox-icon {
        background-color:#3C7D9B;
        border: 3px solid #3C7D9B;
        transition: 0.1s;
    }
    #app .comment-index-form .form-check label .comment-header .comment-created-at{
        font-size:.8em;
    }
    #app .comment-index-form .form-check{
        border-bottom: 2px solid #AAAAAA;
    }
    #app .comment-index-form .form-check .comment-body{
        padding-left: 45px;
    }

</style>

@section('content-with-sidemenu')
<div class="container">

    <h4 class="font-weight-bold mb-5">
        <a href="{{ route('users.blogs.show', ['user' => $user->id, 'blog' => $user->blog->id]) }}" class="text-dark">< 記事管理</a>
    </h4>

    <h2 class="font-weight-bold mb-5">
        コメント管理
    </h2>
    <h3 class="mb-4">
        @status(['color' => $article->status->color]){{$article->status->name}}@endstatus
        {{$article->title}}
    </h3>

    <form class="comment-index-form">
        <div class="form-group row">
           <div class="col-md-12">

            <!-- 全選択機能 -> JavaScriptの実装が必要 -->
                <div class="form-check pl-0 pb-3 mb-3">
                    <input id="commentCheckBoxAll" type="checkbox" style="display:none;">

                    <label for="commentCheckBoxAll" class="d-flex align-items-center">
                        <span class="checkbox-icon" style=""><i class="fas fa-check"></i></span>
                        <p class="col-md-5 mb-0"><strong>全選択</strong></p>

                        <div class="comment-count col-md-6 ml-auto text-pimary d-flex align-items-center justify-content-end">
                            <i class="far fa-comment text-primary" style="font-size:30px; vertical-align:center;"></i>
                            <p class="mb-0 pl-1 pr-3" style="display: inline-block; color: #3C7D9B;">コメント {{count($comments)}}件</p>
                        </div>
                    </label>
                </div>

                @foreach ($comments as $comment)
                    <div class="form-check pl-0 pb-3 mb-3">
                        <input id="{{'comment_'.$comment->id}}" type="checkbox" class="commentCheckbox" style="display:none;">

                        <label for="{{'comment_'.$comment->id}}" class="d-flex align-items-center mb-4">
                            <span class="checkbox-icon" style=""><i class="fas fa-check"></i></span>

                            <div class="comment-header d-flex col-md-11">
                                <img src="{{asset('images/icon/'.$comment->user->icon)}}" alt="comment-icon" style="height: 3em">

                                <div class="ml-3">
                                    <p class="mb-0"><strong>{{$comment->user->name}}</strong></p>
                                    <p class="mb-0 comment-created-at">{{$comment->created_at}}</p>
                                </div>
                            </div>
                        </label>

                        <div class="comment-body mb-5">
                            <p>{{$comment->body}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-group row mt-5 mb-5">
            <button type="submit" class="form-control btn btn-outline-danger col-md-2 offset-md-5">削除する</button>
        </div>
    </form>
</div>
@endsection

<script>

    window.addEventListener('DOMContentLoaded',()=>{
        const allSelectBtn = document.getElementById('commentCheckBoxAll');

        const checkboxes = document.getElementsByClassName('commentCheckbox');

        allSelectBtn.addEventListener('click', (event) => {
            event.target.checked
                ? checkAll(checkboxes)
                : unCheckAll(checkboxes);
        });
    });

    const checkAll = (checkboxes) => {
        Array.prototype.forEach.call(checkboxes, (checkbox)=>{
            checkbox.checked = true;
        })
    }

    const unCheckAll = (checkboxes) => {
        Array.prototype.forEach.call(checkboxes, (checkbox)=>{
            checkbox.checked = false;
        })
    }

</script>
