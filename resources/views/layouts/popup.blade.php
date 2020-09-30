<style>
    .popup-wrapper{
        display:none;

        position:absolute;
        min-height:100%;
        top:0;
        right:0;
        left:0;
        bottom:0;
        margin:0;

        background-color: rgba(70,70,70,0.5);
    }
    .popup-wrapper .container{
        position:relative;
        margin:0 auto;
    }
    .popup-wrapper .container .card{
        position:absolute;
        width:100%;
        height:14em;
        top:0;
        right:0;
        bottom:0;
        left:0;
        margin:auto;
    }
</style>

<div id="popup" class="popup-wrapper row">
    <div class="container col-md-3">
        <div class="card card-default text-center">
            <div class="card-header">
                確認
            </div>
            <div class="card-body">
                <p>本当に削除しますか？</p>
                <a class="btn btn-danger" href="#">削除</a>
                <a class="btn btn-secondary" href="#">キャンセル</a>
            </div>
        </div>
    </div>
</div>