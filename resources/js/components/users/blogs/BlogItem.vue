<template>
    <!-- blog.userが追加されたらレンダリング -->
    <div v-if="blog.user" class="pb-3 mb-5">

        <a class="row col-md-12 text-dark m-0 pr-2 pl-2 mb-2" :href='"/users/"+blog.user.id'>
            <h2 class="col-md-10 p-0">
                <limited-text :text='blog.title' :range='60'/>
            </h2>
            <div class="col-md-2 d-flex flex-row-reverse align-items-center p-0">
                <img :src="'/images/icon/'+ blog.user.icon" alt="user_icon" style="height:1.8rem">
                <p class="mb-0 mr-3">{{ blog.user.name }}</p>
            </div>
        </a>

        <blog-inner-item :baseBlog="blog" />

    </div>
</template>

<script>
import LimitedText from '../../LimitedText';
import BlogInnerItem from './BlogInnerItem.vue';
    export default {
        components: {
            'limited-text': LimitedText,
            'blog-inner-item': BlogInnerItem
        },
        data() {
            return {
                blog: {},
            };
        },
        props:{
            baseBlog: Object
        },
        async mounted(){
            const vm = this;
            const blogData = vm.baseBlog;
            const userRes = await window.axios.get(`/api/users/${blogData.user_id}`);
            blogData.user = userRes.data;

            vm.blog = blogData;
        }
    }
</script>
