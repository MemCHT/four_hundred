<template>
    <div id="blogIndexWrapper" class="blog-index-wrapper mt-5">
        <blog-item
            v-for="blog in blogs"
            v-bind:key="blog.id"
            v-bind:baseBlog="blog"
        />

        <see-more-buttom
            :seeMoreAction="addBlogs()"
        />
    </div>
</template>

<script>
import BlogItem from './BlogItem.vue';
import SeeMoreButton from '../../SeeMoreButton.vue';
    export default {
        components: {
            'blog-item':BlogItem,
            'see-more-buttom':SeeMoreButton
        },
        props: {
            keyword: String
        },
        data() {
            return {
                blogs: [{}],
                offset: 0
            };
        },
        methods:{
            getBlogs:async function(offset = 0){

                const vm = this;

                console.log('access blog-list api');
                // axiosの場合404や500でもcatchしてくれる。またレスポンスがjson形式なので扱いやすい。それ以外はfetchとほぼ使い心地は一緒
                const res = await window.axios.get(`/api/blogs?offset=${offset}&limit=4&sort=newest&${vm.keyword}`);
                const blogData = res.data;
                blogData.forEach(item => console.log(item.id));

                return blogData;
            },
            // アロー関数は呼ばれた場所が"this" ← ず～っとここでつまづいてた。
            // クロージャで内包してほしいthis → IndexContent（ここ）のthis
            // アロー関数のthis → 宣言された時点でのthis、（methodsオブジェクトをthisとしているっぽい）
            // そのためfunction()でなければIndexContentのthisを示せない。
            addBlogs:function(){

                const vm = this;

                return () => {
                    vm.getBlogs(vm.offset).then((data) => {
                        vm.blogs = vm.blogs.concat(data);
                    });
                    vm.offset+=4;
                };
            },
        },
        async mounted(){
            const vm = this;
            vm.blogs = await vm.getBlogs();
            vm.offset+=4;
        }
    }
</script>
