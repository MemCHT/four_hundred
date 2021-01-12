<template>

    <div class="article-index-wrapper mt-5">

        <div class="row mr-0 ml-0">
            <article-item
                v-for="article in articles"
                :key="article.id"
                :baseArticle="article"
            />
        </div>

        <see-more-buttom
            :seeMoreAction="addArticles()"
        />
    </div>
</template>

<script>
import ArticleItem from './Item.vue';
import SeeMoreButton from '../../SeeMoreButton.vue';
    export default {
        components: {
            'article-item':ArticleItem,
            'see-more-buttom':SeeMoreButton
        },
        data() {
            return {
                articles: [{}],
                offset: 0
            };
        },
        props:{
            sort: String,
            keyword: String
        },
        methods:{
            getArticles:async function(offset = 0){

                const vm = this;

                console.log('access article-list api');
                // axiosの場合404や500でもcatchしてくれる。またレスポンスがjson形式なので扱いやすい。それ以外はfetchとほぼ使い心地は一緒
                const res = await window.axios.get(`/api/blogs/0/articles?offset=${offset}&limit=8&sort=${vm.sort}&${vm.keyword}`);
                const articleData = res.data;
                articleData.forEach(item => console.log(item.id));

                return articleData;
            },
            // アロー関数は呼ばれた場所が"this" ← ず～っとここでつまづいてた。
            // クロージャで内包してほしいthis → IndexContent（ここ）のthis
            // アロー関数のthis → 宣言された時点でのthis、（methodsオブジェクトをthisとしているっぽい）
            // そのためfunction()でなければIndexContentのthisを示せない。
            addArticles:function(){

                const vm = this;

                return () => {
                    vm.getArticles(vm.offset).then((data) => {
                        vm.articles = vm.articles.concat(data);
                    });
                    vm.offset+=8;
                };
            },
        },
        async mounted(){
            const vm = this;
            vm.articles = await vm.getArticles();
            vm.offset+=8;
        }
    }
</script>
