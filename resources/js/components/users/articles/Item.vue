<template>
    <!-- article.userが追加されたらレンダリング -->
    <div v-if="article.user && article.blog" class="col-md-6 mb-4">
        <a :href="'/users/'+article.user.id+'/blogs/'+article.blog_id+'/articles/'+article.id"
        class="card card-body p-5" style="color: #333333;">

            <h4 class="mb-3">
                <!-- これもコンポーネント / 関数にしてもよさそう。 -->
                <limited-text :text="article.title" :range="35"/>
                <br>
                <small style="font-size: 0.6em; line-height:1.5em;">
                    <limited-text :text="article.blog.title" :range="50"/>
                </small>
            </h4>

            <p>
                <limited-text :text="article.body" :range="130"/>
            </p>
            <div class="row align-items-center">

                <!-- コメント一覧と同じ、ユーザー表示をコンポーネント化してもよい -->
                <div class="comment-header d-flex align-items-center col-md-6">
                    <user-info :user="article.user" :subInfo="article.updated_at"/>
                </div>

                <div class="col-md-6 d-flex flex-row-reverse">
                    <div class="ml-2">
                        <comment-icon :articleId="article.id"/>
                    </div>
                    <div>
                        <favorite-icon :articleId="article.id" :userId="article.user.id"/>
                    </div>
                </div>
            </div>
        </a>
    </div>

</template>

<script>
import LimitedText from '../../LimitedText';
import CommentIcon from '../../CommentIcon.vue';
import FavoriteIcon from '../../favorites/FavoriteIcon.vue';
import UserInfo from '../../UserInfo.vue';
    export default {
        components: {
            'limited-text': LimitedText,
            'favorite-icon': FavoriteIcon,
            'comment-icon': CommentIcon,
            'user-info': UserInfo
        },
        data() {
            return {
                article: {},
            };
        },
        props:{
            baseArticle: Object
        },
        async mounted(){
            const vm = this;
            const articleData = vm.baseArticle;
            // const userRes = await window.axios.get(`/api/blogs/${vm.baseArticle.blog_id}/user`);
            // articleData.user = userRes.data;

            // vm.article = articleData;
            if(articleData.blog_id){
                const urls = [`/api/blogs/${articleData.blog_id}/user`, `/api/blogs/${articleData.blog_id}`];
                const [userRes, blogRes] =await Promise.all(urls.map((url) => window.axios.get(url)));
                [articleData.user, articleData.blog] = [userRes.data, blogRes.data];
                vm.article = articleData;
            }
        }
    }
</script>
