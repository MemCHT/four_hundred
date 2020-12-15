window.addEventListener('DOMContentLoaded', () => {
    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = () => {

        /**
         * xhr.readyStateの種類
         *  0	UNSENT	クライアントは作成済み。open() はまだ呼ばれていない。
         *  1	OPENED	open() が呼び出し済み。
         *  2	HEADERS_RECEIVED	send() が呼び出し済みで、ヘッダーとステータスが利用可能。
         *  3	LOADING	ダウンロード中。responseText には部分データが入っている。
         *  4	DONE	操作が完了した。
         */
        if(xhr.readyState === 4 && xhr.status === 200)
            console.log( xhr.responseText );

        console.log( xhr.status );
    }

    xhr.open('GET', 'http://localhost/api/test');
    xhr.send();

    // alert('hogehoge');
});
