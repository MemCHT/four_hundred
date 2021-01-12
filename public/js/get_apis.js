/*window.addEventListener('DOMContentLoaded', () => {

    getData('http://localhost/api/test')
    .then((data) => {
        console.log(data);
    }).catch((e) => {
        console.log(e.message);
    });

});*/

// ※fetchAPIで書き直したほうが良いかも。

// urlを渡してGETリクエストをし、成功なら取得したデータのJSONを、失敗ならステータステキストをもとにエラーを返す
const get = (url) => {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', url);

        xhr.onload = () => {
            if(xhr.status === 200)
                resolve( xhr.responseText );
        };
        xhr.onerror = () => {
            reject( new Error(xhr.statusText) );
        };

        xhr.send();
    });
};

const http = {
    get: get,
};

export default http;
