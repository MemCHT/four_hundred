@extends(Auth::guard('admin')->check() ? 'layouts.admin.app' : 'layouts.user.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <!--<div class="title-wrapper mt-5">
                <div class="row">
                    <div class="col-md-12">
                        <h2> ブログ一覧 </h2>
                    </div>
                </div>
            </div>-->

            <div id="blogIndexWrapper" class="blog-index-wrapper mt-5">
                @foreach($blogs as $blog)
                    <div class="pb-3 mb-5">

                        <a class="row col-md-12 text-dark m-0 pr-2 pl-2 mb-2" href="{{ route('users.show', ['user' => $blog->user_id]) }}">
                            <h2 class="col-md-10 p-0">
                                {{ strlen($blog->title) > 60 ? substr($blog->title,0 , 60).'...' : $blog->title }}
                            </h2>
                            <div class="col-md-2 d-flex flex-row-reverse align-items-center p-0">
                                <img src="{{ asset('/images/icon/'.$blog->user->icon) }}" alt="user_icon" style="height:1.8rem">
                                <p class="mb-0 mr-3">{{ $blog->user->name }}</p>
                            </div>
                        </a>

                        @if($blog->getArticles(1)->isEmpty() === false)
                            <div class="row mr-0 ml-0">
                                @foreach($blog->getArticles(3) as $article)
                                    <div class="col-md-4 pr-2 pl-2">
                                        <a href="{{ route('users.blogs.articles.show', ['user' => $article->blog->user->id, 'blog' => $article->blog_id, 'article' => $article->id]) }}"
                                            class="card card-body text-dark p-5">

                                            <h4 class="mb-3">{{ strlen($article->title) > 15 ? substr($article->title,0 , 15).'...' : $article->title }}</h4>
                                            <p>{{ strlen($article->body) > 75 ? substr($article->body,0 , 75).'...' : $article->body }}</p>
                                            <div class="row">
                                                <div class="col-md-6 d-flex">
                                                    <div>
                                                        @favorite(['article' => $article, 'canSubmit' => false]){{ count($article->favorites) }}@endfavorite
                                                    </div>
                                                    <div class="ml-2">
                                                        @comment{{ count($article->comments) }}@endcomment
                                                    </div>
                                                </div>
                                                <div class="col-md-6 text-right text-secondary">{{ $article->updated_at->format('Y/m/d') }}</div>
                                            </div>
                                        </a>
                                    </div>

                                @endforeach
                            </div>
                        @else
                            <h4 class="text-secondary pr-2 pl-2">公開中の記事がありません</h4>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="text-center mb-4">
                <button class="btn btn-primary col-md-4">もっと見る</button>
            </div>
            {{$blogs->appends($input)->links('vendor.pagination.modified')}}
        </div>
    </div>
</div>
@endsection

<!-- TODO_public直接js追加と考察、動作確認 -->
<script type='module'>
    // import http from '/js/get_apis.js';

    window.addEventListener('DOMContentLoaded', () => {
        appendBlogElements();
    });

    // ブログ一覧用の要素を取得
    const appendBlogElements = async () => {

        const wrapper = document.getElementById('blogIndexWrapper');

        try{
            // fragmentならタグを生成せずにまとめられる。
            // おそらくforEachにawait（非同期処理）が入っている関係上、fragmentでまとめて追加できない。
            // const fragment = document.createDocumentFragment();

            // ブログリストを新着順に4件取得
            const dataList = await fetch('http://localhost/api/blogs?offset=0&limit=4&sort=newest').then(res => res.json());

            // console.log(data);

            // ブログリストからhtml要素へ変換
            dataList.forEach(async (data) => {
                const element = await createBlogElement(data);
                // fragment.appendChild(element);
                wrapper.appendChild(element);
            });

            // 生成されたhtml要素を追加
            // wrapper.appendChild(fragment);

            // console.log(fragment);


        }catch(e){
            console.log(e);
        }

    };

    /**
     * 要素一つ分のJSONを受け取り、ブログ要素を作成する。
     * @param {object} data リクエストで得られるBlog一つ分のJSONデータ
     */
    const createBlogElement = async (blogData) => {
        const element = document.createElement('div');
        element.classList.add('blog-item', 'pb-3', 'mb-5');

        //const content = blogData['title'];
        //element.innerHTML = content;

        const articleElements = await createArticleElements( blogData );

        console.log(articleElements);

        element.appendChild( articleElements );

        console.log(element);

        return element;
    };

    const createUserElement = (userData) => {
        const content =
        `
            <a class="row col-md-12 text-dark m-0 pr-2 pl-2 mb-2" href="{{ route('users.show', ['user' => $blog->user_id]) }}">
                <h2 class="col-md-10 p-0">
                    {{ strlen($blog->title) > 60 ? substr($blog->title,0 , 60).'...' : $blog->title }}
                </h2>
                <div class="col-md-2 d-flex flex-row-reverse align-items-center p-0">
                    <img src="{{ asset('/images/icon/'.$blog->user->icon) }}" alt="user_icon" style="height:1.8rem">
                    <p class="mb-0 mr-3">{{ $blog->user->name }}</p>
                </div>
            </a>
        `;
    }

    const createArticleElements = async (blogData) => {
        try{
            const articleDataList = await fetch(`http://localhost/api/blogs/${blogData['id']}/articles?offset=0&limit=3&sort=newest`).then(res => res.json());
            console.log(articleDataList);

            const wrapperElement = document.createElement('div');
            wrapperElement.classList.add('articlesWrapper', 'row', 'mr-0', 'ml-0');


            // const fragment = document.createDocumentFragment();

            articleDataList.forEach((articleData) => {
                const element = createArticleElement(articleData, blogData);
                // fragment.appendChild(element);
                wrapperElement.appendChild(element);
            });

            return wrapperElement;

        }catch(e){
            console.log(e);
        }
    };

    const createArticleElement = (articleData, blogData) => {

        const element = document.createElement('div');
        element.classList.add('articleWrapper', 'col-md-4', 'pr-2', 'pl-2');

        // 動的にリンクや他コンポーネントを生成するには、今の場合直書きしないといけない...（PHPはサーバーサイドでしか発行されないので。）
        // ここにきてVueやReactを取り入れる理由がわかった気がする。

        const content =
        `
            <a href="${ 'http://localhost/users/'+blogData['user_id']+'/blogs/'+articleData['blog_id']+'/articles/'+articleData['id'] /* 修正箇所 */ }"
                class="card card-body text-dark p-5">

                <h4 class="mb-3">${limitTextToRange(articleData['title'], 15)}</h4>
                <p>${limitTextToRange(articleData['body'], 75)}</p>
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div>
                            ${'💛'/*favorite(['article' => $article, 'canSubmit' => false]){{ count($article->favorites) }} endfavorite*/}
                        </div>
                        <div class="ml-2">
                            ${'💭'/*comment{{ count($article->comments) }} endcomment*/}
                        </div>
                    </div>
                    <div class="col-md-6 text-right text-secondary">${articleData['updated_at']/*{{ $article->updated_at->format('Y/m/d') }}*/}</div>
                </div>
            </a>
        `;

        element.innerHTML= content;
        // console.log(element);
        return element;
    }

    const limitTextToRange = (text, range) => {
        return text.length > range ? text.substring(0, range)+'...' : text;
    }
</script>
