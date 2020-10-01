<!-- classに"btn-delete"とついている要素とセットで使うコンポーネント -->

<div id="popup" class="popup-wrapper row">
    <div class="container col-md-3">
        <div class="card card-default text-center">
            <div class="card-header">
                確認
            </div>
            <div class="card-body">
                <p>本当に削除しますか？</p>
                <button id="popup-btn-apply" class="btn btn-danger">削除</button>
                <button id="popup-btn-cancel" class="btn btn-secondary">キャンセル</button>
            </div>
        </div>
    </div>
</div>

<form name="popupFormDelete" action="{{ $route }}" method="POST">
    @method('DELETE')
    @csrf
</form>