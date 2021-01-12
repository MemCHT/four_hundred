<template>
    <div class="component-comment icon">
        <i class="far fa-comment text-primary"></i>
        <span class="text-primary">{{ count }}</span>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                count: 0
            };
        },
        props:{
            articleId: Number
        },
        async mounted(){
            const vm = this;
            const commentsRes = await window.axios.get(`/api/articles/${vm.articleId}/comments`);
            vm.count = commentsRes.data.length;
        }
    }
</script>
