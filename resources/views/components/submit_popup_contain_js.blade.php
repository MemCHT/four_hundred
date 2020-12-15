<!--
    フォーム送信時に送信確認用ポップアップを間に挟むためのコンポーネント。
    ※onclickを取得するので、onclickの中にsubmit必須。
    ※target_idsはデザイン上イベントが付与されたボタンがいくつもある場合、コンポーネントのinclude先で工夫して取得する必要がある。
    ※コンポーネントinclude先でメソッド名等かぶらないようにform_idを用いている。

    ▼使用例
    include('components.submit_popup_contain_js',[
        'form_id' => 'logout_form',             ...submitするフォームのid
        'target_ids' => ['logout_button'],         ...クリックするボタンやaタグリンクのid（配列で渡す）
        'message' => 'ログアウトしますか？',      ...主メッセージ
        'sub_message' => ''                     ...副メッセージ
        'accept' => 'はい',                     ...承認ボタンテキスト
        'reject' => 'いいえ'                    ...拒否ボタンテキスト
    ])

-->

<style>
    #{{ $form_id.'_submitPopup' }} {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1001;
        background-color: rgba(0,0,0,0.3);
        display: none;
    }
    #{{ $form_id.'_submitPopup' }} > div{
        width: 100%;
        height: 100%;
    }

    #{{ $form_id.'_submitPopup' }} .card .card-body{
        padding: 4em 2em;
        text-align: center;
    }

    #{{ $form_id.'_submitPopup' }} .card .card-body h5{
        font-weight: bold;
    }
    #{{ $form_id.'_submitPopup' }} .card .card-body .d-flex{
        width: 30em;
    }
    #{{ $form_id.'_submitPopup' }} .card .card-body .d-flex button{
        flex: 1;
        margin: 0 0.25em;
        padding-top: 0.125em;
        padding-bottom: 0.125em;
    }
</style>

<div id="{{ $form_id.'_submitPopup' }}" class="cover-background">
    <div class="d-flex justify-content-center align-items-center">
        <div class="card">
            <div class="card-body">
                <div class="mr-4 ml-4 mb-4 text-left">
                    <h5 id="{{ $form_id.'_message' }}" class="mb-4">{{ $message }}</h5>
                    <p id="{{ $form_id.'_subMessage' }}">{!! $sub_message ?? '' !!}</p>
                </div>
                <div class="d-flex">
                    <button id="{{ $form_id.'_submitReject' }}" class="btn btn-outline-primary mr-3">
                        {{ $reject ?? 'いいえ' }}
                    </button>
                    <button id="{{ $form_id.'_submitAccept' }}" class="btn btn-outline-danger ml-3">
                        {{ $accept ?? 'はい' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', () => {

        const acceptBtn = document.getElementById('{{$form_id."_submitAccept"}}');
        const rejectBtn = document.getElementById('{{$form_id."_submitReject"}}');

        // const form = document.getElementById('{{ $form_id }}');

        // popupを拒否、何も起きない
        rejectBtn.addEventListener('click', {{ $form_id.'_' }}submitReject);

        let targets = [];

        @foreach($target_ids as $index => $target_id)
            targets.push(document.getElementById('{{ $target_id }}'));
        @endforeach

        targets.forEach((target) => {
            // あらかじめデフォルトの動作を格納、popupを承認してデフォルトの動作を呼ぶ。
            // 本来はbind(thisArg) ... 例えば console.log(this.name)等でのthisをthisArgに置き換えるものだが、その時新しい関数を生成して返すためそれを利用。
            // console.log(target.onclick);
            const defaultOnClick = target.onclick.bind({});

            // 後から追加したイベントのpreventDefault()で無効化できなかったので、この段階でデフォルトイベントを破棄。
            target.onclick = (event) => { event.preventDefault(); };

            // acceptBtn.addEventListener('click', submitAcceptClosure(defaultOnClick));

            // form送信のイベントが登録されている要素に、popupを表示するメソッドを上書きしている。
            target.addEventListener('click', {{ $form_id.'_' }}handleClickClosure(defaultOnClick));
        });
    });

    const {{ $form_id.'_' }}handleClickClosure = (defaultEvent) => {
        // targetのデフォルトイベントにあるeventを渡すためのクロージャ

        const acceptBtn = document.getElementById('{{$form_id."_submitAccept"}}');

        const handleClick = (event) => {
            event.preventDefault();
            {{ $form_id.'_' }}popupWillAppear();

            const submitPopup = document.getElementById('{{ $form_id."_submitPopup" }}');

            submitPopup.style.display = 'block';

            acceptBtn.addEventListener('click', {{ $form_id.'_' }}submitAcceptClosure(defaultEvent));
        };

        return handleClick;
    };

    const {{ $form_id.'_' }}submitAcceptClosure = (defaultEvent) => {
        // targetのデフォルトイベントにあるeventを渡すためのクロージャ
        const submitAccept = (event) => {
            defaultEvent(event);
        };

        return submitAccept;
    };

    const {{ $form_id.'_' }}submitReject = (event) => {
        const popup = document.getElementById('{{ $form_id."_submitPopup" }}');
        popup.style.display = 'none';
    };

    // popupが表示される直前に行う処理を受け取る。
    const {{ $form_id.'_' }}popupWillAppear = () => {
        {!! $popupWillAppear ?? '' !!}
    };
</script>
