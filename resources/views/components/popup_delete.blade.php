<!-- idに"btn-delete_N"、classに"btn-delete"と付与されている要素とセットで使うコンポーネント-->
<!-- ※Nは削除したいテーブルのレコードid -->

<div id="popup" class="popup-wrapper row" style="display:none;">
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

<script src="{{ asset('js/popup_delete.js') }}"></script>
<script>
    /**
     * 必要な要素を渡すためのメソッド
     */
    window.addEventListener('DOMContentLoaded', function(){

        const btn_delete = document.getElementsByClassName('btn-delete');

        if(btn_delete){
            setDeletePopupEvent({method:'{{ method_field('DELETE') }}', token:'{{ csrf_field() }}', url: '{{ $route }}'});
        }
    });
</script>