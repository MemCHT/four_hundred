<!-- ユーザー一つの概要を表すカード用コンポーネント -->
<!-- ['user' => ユーザーのモデルデータ, 'target_id' => ユーザー停止ボタンをsubmitするボタンのid] -->

<div class="card user-card p-4">
    <div class="card-body">
        <div class="d-flex">
            <div style="flex:5;">
                <h3 class="mb-3">@include('components.text_substring',['text' => $user->blog->title, 'length' => 25])</h3>
            </div>
            <div class="text-right" style="flex:1;">
                <button class="btn btn-outline-danger" {{$user->status->name == '公開' ? 'hidden' : ''}} disabled>停止中</button>
            </div>
        </div>

        <div class="mb-4">
            @include('components.user',['user' => $user, 'sub_info' => $user->email])
        </div>

        <div class="d-flex">
            <div class="text-primary" style="flex:1;">
                <form action="{{ route('admins.users.sendmail', ['user' => $user->id]) }}" method="POST">
                    @csrf

                    <button name="btn" value="btn" class="btn btn-outline-primary col-md-11">メールを送る</button>
                </form>
            </div>

            <?php $user_freeze_form_id = 'userFreezeForm_'.$user->id ?>

            <div class="text-danger text-right" style="flex:1;">
                <form id="{{ $user_freeze_form_id }}" action="{{ route('admins.users.update', ['user' => $user->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if($user->status->name == '公開')
                        <input name="status_id" value="{{ $user->status->getByName('非公開')->id }}" hidden>
                        <button id="{{ $target_id }}" class="btn btn-outline-danger col-md-11"
                            onclick="{{ $user_freeze_form_id }}.submit();"
                        >
                            アカウントを停止する
                        </button>
                    @else
                        <input name="status_id" value="{{ $user->status->getByName('公開')->id }}" hidden>
                        <button id="{{ $target_id }}" class="btn btn-danger col-md-11"
                            onclick="{{ $user_freeze_form_id }}.submit();"
                        >
                            アカウントを再開する
                        </button>
                    @endif

                </form>
            </div>
        </div>
    </div>
</div>
