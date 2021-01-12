<template>

    <div v-if="blog.articles && blog.articles.length > 0" class="row mr-0 ml-0">

            <div
                v-for="article in blog.articles"
                :key="article.id"
                class="col-md-4 pr-2 pl-2"
            >
                <a :href="'/users/'+blog.user_id+'/blogs/'+blog.id+'/articles/'+article.id"
                    class="card card-body text-dark p-5">

                    <h4 class="mb-3"><limited-text :text='article.title' :range='15'/></h4>
                    <limited-text :text='article.body' :range='75'/>
                    <div class="row">
                        <div class="col-md-6 d-flex">
                            <div>
                                <favorite-icon :articleId="article.id" :userId="blog.user_id"/>
                            </div>
                            <div class="ml-2">
                                <comment-icon :articleId="article.id"/>
                            </div>
                        </div>
                        <div class="col-md-6 text-right text-secondary">{{ article.updated_at | moment }}</div>
                    </div>
                </a>
            </div>
    </div>

    <h4 v-else class="text-secondary pr-2 pl-2">公開中の記事がありません</h4>

</template>

<script>
import moment from 'moment';
import LimitedText from '../../LimitedText.vue';
import CommentIcon from '../../CommentIcon.vue';
import FavoriteIcon from '../../favorites/FavoriteIcon.vue';
    export default {
        components: {
            'limited-text': LimitedText,
            'comment-icon': CommentIcon,
            'favorite-icon': FavoriteIcon
        },
        data() {
            return {
                blog: {},
            };
        },
        props:{
            baseBlog: Object
        },
        filters: {
            moment: (date)=>{
                return moment(date).format('YYYY/MM/DD');
            }
        },
        async mounted(){
            const vm = this;
            const blogData = vm.baseBlog
            const articlesRes = await window.axios.get(`/api/blogs/${blogData.id}/articles?offset=0&limit=3&sort=newest`);
            blogData.articles = articlesRes.data;
            vm.blog = blogData;
        }
    }
</script>
