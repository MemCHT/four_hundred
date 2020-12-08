@extends('layouts.admin.app')

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

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <h2 class="col-md-4 font-weight-bold">コメント管理</h2>

        <div class="col-md-8">
            <form class="d-flex">
                <div style="flex:1;">
                    <div class="pr-2">@include('components.search', [
                        'name' => 'userName',
                        'value' => request()->input('userName') ?? '',
                        'placeholder' => 'ユーザー名で検索'
                    ])</div>
                </div>
                <div style="flex:1;">
                    <div class="pl-2">@include('components.search', [
                        'name' => 'articleTitle',
                        'value' => request()->input('articleTitle') ?? '',
                        'placeholder' => '記事タイトルで検索'
                    ])</div>
                </div>
            </form>
        </div>

        <div class="col-md-12 mt-5">
            @if(isset($comments[0]))
            <form class="comment-index-form" id="commentManageForm" name="commentManageForm" method="POST" action="{{ route("admins.comments.update", ["comment" => 0]) }}">
                @csrf
                {{ method_field("PUT") }}

                <div class="form-group row">
                    <div class="col-md-12">


                        <!-- 全選択機能 -->
                        <div class="form-check pl-0 pb-3 mb-3">
                            <input id="commentCheckBoxAll" type="checkbox" style="display:none;">

                            <label for="commentCheckBoxAll" class="d-flex align-items-center">
                                <div style="flex:1;">
                                    <span class="checkbox-icon" style="display:inline-block;"><i class="fas fa-check"></i></span>
                                    <p class="ml-3" style="display:inline;"><strong>全選択</strong></p>
                                </div>

                                <div class="d-flex" style="flex:3;">
                                    <div class="mr-2" style="flex:1;">
                                        <button type="submit" class="btn btn-outline-primary btn-block" id="btnToPublic"
                                            onclick="commentManageForm.submitType.value='toPublic'">公開する</button>
                                    </div>
                                    <div class="mr-2 ml-2" style="flex:1;">
                                        <button type="submit" class="btn btn-outline-secondary btn-block" id="btnToPrivate"
                                            onclick="commentManageForm.submitType.value='toPrivate'">非公開にする</button>
                                    </div>
                                    <div class="ml-2" style="flex:1;">
                                        <button type="submit" class="btn btn-outline-danger btn-block" id="btnDelete"
                                            onclick="commentManageForm.submitType.value='delete'">削除する</button>
                                    </div>
                                    <input name="submitType" value="" hidden >

                                </div>

                                <div class="comment-count text-pimary d-flex align-items-center justify-content-end" style="flex:1;">
                                    <i class="far fa-comment text-primary" style="font-size:30px; vertical-align:center;"></i>
                                    <p class="mb-0 pl-1 pr-3" style="display: inline-block; color: #3C7D9B;">コメント {{$comments_count}}件</p>
                                </div>
                            </label>
                        </div>

                        @foreach ($comments as $comment)
                            <div class="form-check pl-0 pb-3 mb-3">
                                <input id="{{'comment_'.$comment->id}}" name="{{'comment_'.$comment->id}}" value="{{ $comment->id }}" type="checkbox" class="commentCheckbox" style="display:none;">

                                <label for="{{'comment_'.$comment->id}}" class="d-flex align-items-center mb-4">
                                    <span class="checkbox-icon" style=""><i class="fas fa-check"></i></span>

                                    <div class="comment-header col-md-11 d-flex align-items-center">
                                        <div class="mr-3" style="font-size: 2em; flex: 1;">
                                            @include('components.status', ['slot' => $comment->status->name, 'color' => $comment->status->color])
                                        </div>
                                        <div style="flex: 3;">
                                            @include('components.user',['user' => $comment->user, 'sub_info' => $comment->created_at])
                                        </div>
                                        <h3 class="ml-4 pl-3" style="border-left: 3px solid #AAAAAA; flex: 10;">
                                            @include('components.text_substring', ['text' => $comment->article->title, 'length' => 50])
                                        </h3>
                                    </div>
                                </label>

                                <div class="comment-body mb-5">
                                    <p>{{$comment->body}}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </form>
            @else
                <h3 class="text-secondary mt-5">
                    コメントが投稿されていません。
                </h3>
            @endif
        </div>

        <div class="mt-4">
            {{ $comments->appends(request()->input())->links('vendor.pagination.modified') }}
        </div>
    </div>
</div>
@endsection

<script>
    const submitTypes = {toPublic: 'toPublic', toPrivate: 'toPrivate', delete: 'delete'};

    window.addEventListener('DOMContentLoaded',()=>{
        createCheckBoxes();

        const btnToPublic = document.getElementById('btnToPublic');
        const btnToPrivate = document.getElementById('btnToPrivate');
        const btnDelete = document.getElementById('btnDelete');
        const btns = {toPublic: btnToPublic, toPrivate: btnToPrivate, delete: btnDelete};

        for(let [key, btn] of Object.entries(btns)){
            btn.addEventListener('click', () => { commentManageForm.submitType.value = submitTypes[key] });
        }

        document.commentManageForm.addEventListener('submit', handleSubmit);
    });

    // event.currentTarget === document.commentManageForm: HTMLFormElement
    const handleSubmit = (event) => {
        event.preventDefault();

        let form = document.commentManageForm;

        // なぜかjsのform.submit()だと送信されない。
        // 無理やり新しいフォーム要素作って送ったら送信された。
        // えぇ... もしやと思ってinputのtypeを"checkbox"から"text"に変えたら送信された💢。
        Array.from(form.elements).forEach((element) => {
            if(element.checked)
                element.type = "text";
        })
        form = form.submitType.value === submitTypes['delete']
                 ? submitDelete(form)
                 : submitUpdate(form);

        form.submit();
    }

    const submitUpdate = (form) => {
        // alert('来てるよん');
        form.innerHTML += '{{ method_field("PUT") }}';
        form.action = '{{ route("admins.comments.update", ["comment" => 0]) }}';

        return form;
    }
    const submitDelete = (form) => {
        form.innerHTML += '{{ method_field("DELETE") }}';
        form.action = '{{ route("admins.comments.delete") }}';

        return form;
    }

    /**
     * 全選択機能を実装するメソッド
     */
    const createCheckBoxes = () => {
        const allSelectBtn = document.getElementById('commentCheckBoxAll');

        if(!allSelectBtn)
            return;

        const checkboxes = document.getElementsByClassName('commentCheckbox');

        allSelectBtn.addEventListener('click', (event) => {

            if(event.target.checked)
                checkAll(checkboxes);
            else
                unCheckAll(checkboxes);
        });
    }

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
