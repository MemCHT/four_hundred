<template>
    <!--@if($blog->getArticles(1)->isEmpty() === false)-->

        <div v-if="blog.articles" class="row mr-0 ml-0">

                <!-- TODO_20210106 データがうまく取得できていない問題の対応＆Laravel依存性注入を調査したい -->
                <div
                    v-for="article in blog.articles"
                    :key="article.id"
                    class="col-md-4 pr-2 pl-2"
                >
                    <!--<a href="{{ route('users.blogs.articles.show', ['user' => $article->blog->user->id, 'blog' => $article->blog_id, 'article' => $article->id]) }}"
                        class="card card-body text-dark p-5">-->
                    <!-- href例 "/users/1/blogs/1/articles/1" -->
                    <a :href="'/users/'+blog.user_id+'/blogs/'+blog.id+'/articles/'+article.id"
                        class="card card-body text-dark p-5">

                        <!--<h4 class="mb-3">{{ strlen($article->title) > 15 ? substr($article->title,0 , 15).'...' : $article->title }}</h4>-->
                        <h4 class="mb-3"><limited-text :text='article.title' :range='15'/></h4>
                        <!--<p>{{ strlen($article->body) > 75 ? substr($article->body,0 , 75).'...' : $article->body }}</p>-->
                        <limited-text :text='article.body' :range='75'/>
                        <div class="row">
                            <div class="col-md-6 d-flex">
                                <!--
                                <div>
                                    @favorite(['article' => $article, 'canSubmit' => false]){{ count($article->favorites) }}@endfavorite
                                </div>
                                -->
                                <!--
                                <div class="ml-2">
                                    @comment{{ count($article->comments) }}@endcomment
                                </div>
                                -->
                            </div>
                            <div class="col-md-6 text-right text-secondary">{{ article.updated_at | moment }}</div>
                        </div>
                    </a>
                </div>
        </div>

    <!--@else
        <h4 class="text-secondary pr-2 pl-2">公開中の記事がありません</h4>
    @endif-->
</template>

<script>
import moment from 'moment';
import LimitedText from '../../LimitedText.vue';
    export default {
        components: {
            'limited-text': LimitedText
        },
        data() {
            return {
                blog: {},
            };
        },
        props:{
            baseBlog: {}
        },
        filters: {
            moment: (date)=>{
                return moment(date).format('YYYY/MM/DD');
            }
        },
        async mounted(){
            const vm = this;
            const blogData = vm.baseBlog
            const articlesRes = await window.axios.get(`/api/blogs/${vm.baseBlog.id}/articles?offset=0&limit=3?sort=newest`);
            blogData.articles = articlesRes.data;
            vm.blog = blogData;
        }
    }
</script>
