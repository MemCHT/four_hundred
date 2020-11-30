<div class="card user-card p-4">
    <div class="card-body">
        <h3 class="mb-3">@include('components.text_substring',['text' => $user->blog->title, 'length' => 25])</h3>

        <div class="mb-4">
            @include('components.user',['user' => $user, 'sub_info' => $user->email])
        </div>

        <div class="d-flex">
            <div class="text-primary" style="flex:1;">
                <button class="btn btn-outline-primary col-md-11">メールを送る</button>
            </div>
            <div class="text-danger text-right" style="flex:1;">
                <button class="btn btn-outline-danger col-md-11">アカウントを停止する</button>
            </div>
        </div>
    </div>
</div>
