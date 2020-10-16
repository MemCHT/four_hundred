/**
 * views\components\popup_delete.blade.phpで用いるスクリプト
 */


/**
 * 削除ボタン(classに"btn-delete"を持つ要素)にポップアップを表示するイベントをセットする
 * 
 * @param {object} form_elements
 * form_elements = {method:'method_field', token:'csrf_token', url: 'route'}
 */
const setDeletePopupEvent = (form_elements) => {
    const btn_delete = document.getElementsByClassName('btn-delete');

    //btn_deleteクラスが適用されている全ての要素にイベントを付与
    Array.from(btn_delete).forEach((element) => {
        element.addEventListener('click',(event) => {
            event.preventDefault();
            $popup = document.getElementById('popup');
            $popup.style.display='flex';
            $popup.style.height = document.getElementById('app').clientHeight;  //コンテンツ縦幅に合わせる。


            //送信用フォームエレメントを作成
            const button_id = event.currentTarget.id;
            const delete_form = createDeleteForm(button_id,form_elements);

            //送信用フォームエレメントを適用
            setApplyEvent(delete_form);
            setCancelEvent();
        });
    });
}

/**
 * ポップアップの確定ボタンにイベントをセットする
 * 
 * @param {object} delete_form
 * delete_form = {method:'method_field', token:'csrf_token', url: 'route'}
 */
const setApplyEvent = (delete_form) => {
    const btn_apply = document.getElementById('popup-btn-apply');
    //popup-btn-apply というidをもつ要素があったときに、applyイベントをセット
    if(btn_apply){
        btn_apply.addEventListener('click',(event) => apply(event, delete_form));
    }
}
//削除を適用するイベントapply()
const apply = (event, delete_form) => {
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
const createDeleteForm = (button_id,form_elements) => {
    /**
     * delete実行用タグ, token送信用タグを取得
     */

    const form = document.createElement('form');

    const url = formatUrl(form_elements['url']);

    form.action = url + '/' + button_id.split('_')[1];
    form.method = 'POST';

    form.innerHTML+=form_elements['method'];
    form.innerHTML+=form_elements['token'];

    form.id='delete-form';
    document.body.append(form);

    return form;
}

const submitDeleteForm = (form) => {
    form.submit();
}

/**
 * route()で指定されたdestroy用のurlを受け取って、末尾の余計なパラメータ(/users/1/blogs/1/article/xx 等)を削除して返す
 * @param {string} url 
 */
const formatUrl = (url) => {
    let count=1;
    while(true){
        if(url.slice(-count).match(/\//)){
            return url.slice(0, -count);
        }
        count++;
        //console.log(count);
    }
}


/**
 * ポップアップのキャンセルボタンにイベントをセットする
 */
const setCancelEvent = () => {
    const btn_cancel = document.getElementById('popup-btn-cancel');
    //popup-btn-cancel というidをもつ要素があったときに、cancelイベントをセット
    if(btn_cancel){
        btn_cancel.addEventListener('click',(event) => cancel(event));
    }
}
//ポップアップをキャンセルするイベントcancel()
const cancel = (event) => {
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