window.addEventListener('load', function(){

    const btn_delete = document.getElementsByClassName('btn-delete');

    if(btn_delete){
        setDeletePopupEvent();
    }
});

/**
 * 削除ボタン(classに"btn-delete"を持つ要素)にポップアップを表示するイベントをセットします。
 */
function setDeletePopupEvent(){
    const btn_delete = document.getElementsByClassName('btn-delete');

    Array.from(btn_delete).forEach((element) => {
        element.addEventListener('click',(event) => {
            event.preventDefault();
            document.getElementById('popup').style.display='flex';

            //送信用フォームエレメントを作成
            let button_id = event.currentTarget.id;
            const delete_form = createDeleteForm(button_id);

            setApplyEvent(delete_form);
            setCancelEvent();
        });
    });
}

/**
 * ポップアップの確定ボタンにイベントをセットします。
 */
function setApplyEvent(delete_form){
    const btn_apply = document.getElementById('popup-btn-apply');
    if(btn_apply){
        btn_apply.addEventListener('click',(event) => apply(event, delete_form));
    }
}

function apply(event, delete_form){
    event.preventDefault();
    if(delete_form){
        delete_form.submit();
    }
}


/*
    生成する削除用フォーム例
    <form id="delete-form" action="http://localhost/users/1/blogs/1/articles/33/comments/324" method="POST">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="aZW8PHGLSL72ajppQjJacOWv4lZP9E6IZogYO4jx">
    </form>
 */

/**
 * 削除ボタンにつけられたidを取得して、削除用フォームを生成して返す
 * @param {string} button_id 
 * @return {HTMLElement} form
 */
function createDeleteForm(button_id){
    /**
     * delete実行用タグ, token送信用タグを取得
     * 
     * ※メソッドはvies/components/popup_delete.bladeに記述されている。
     */
    const form_elements = getFormElements();

    let form = document.createElement('form');

    let url = formatUrl(form_elements['url']);

    form.action = url + '/' + button_id.split('_')[1];
    form.method = 'POST';

    form.innerHTML+=form_elements['method'];
    form.innerHTML+=form_elements['token'];

    form.id='delete-form';
    document.body.append(form);

    return form;
}

function submitDeleteForm(form){
    form.submit();
}

/**
 * route()で指定されたdestroy用のurlを受け取って、末尾の余計なパラメータ(/users/1/blogs/1/article/xx 等)を削除して返す
 * @param {string} url 
 */
function formatUrl(url){
    let count=1;
    while(true){
        if(url.slice(-count).match(/\//)){
            return url.slice(0, -count);
        }
        count++;
        console.log(count);
    }
}


/**
 * ポップアップのキャンセルボタンにイベントをセットする。
 */
function setCancelEvent(){
    const btn_cancel = document.getElementById('popup-btn-cancel');
    if(btn_cancel){
        btn_cancel.addEventListener('click',(event) => cancel(event));
    }
}

function cancel(event){
    event.preventDefault();
    document.getElementById('popup').setAttribute('style','display:none;');

    /**
     * キャンセル時に、削除ボタンを押して作られた削除用フォームを除去する
     */
    const delete_form = document.getElementById('delete-form');
    if(delete_form){
        delete_form.remove();
    }
}