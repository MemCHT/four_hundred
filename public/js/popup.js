window.addEventListener('DOMContentLoaded', function(){

  const btn_delete = document.getElementsByClassName('btn-delete');

  if(btn_delete){
      setDeletePopupEvent();
      setApplyEvent();
      setCancelEvent();
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
      });
  });
}

/**
* ポップアップの確定ボタンにイベントをセットします。
*/
function setApplyEvent(){
  const btn_apply = document.getElementById('popup-btn-apply');
  if(btn_apply){
      btn_apply.addEventListener('click',(event) => apply(event));
  }
}

function apply(event){
  event.preventDefault();
  const form_delete = document.popupFormDelete;
  if(form_delete){
      form_delete.submit();
  }
}

/**
* ポップアップのキャンセルボタンにイベントをセットします。
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
}