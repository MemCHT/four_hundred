<template>

    <!--exists（ユーザーがお気に入り登録しているかどうか）によってアイコンを変える -->
    <div v-if="exists" class="component-favorite icon">
            <i class="fas fa-heart text-danger"></i><span> {{ count }}</span>
    </div>

    <div v-else class="component-favorite icon">
        <i class="far fa-heart text-danger"></i><span> {{ count }}</span>
    </div>

</template>

<script>
    export default {
        data(){
            return {
                count: 0,
                exists: false
            };
        },
        props:{
            articleId: Number,
            userId: Number
        },
        async mounted() {
            const vm = this;
            const urls = [`/api/articles/${vm.articleId}/favorites`, `/api/articles/${vm.articleId}/favorites/exists`];
            const [favoritesRes, existsCurrentUserRes] = await Promise.all(urls.map((url)=>window.axios.get(url))); // やや冗長だが、関数をそのまま渡したらエラーになってしまう。

            vm.count = favoritesRes.data.length;

            // レスポンスとして直接booleanを受け取れないため、falsyなレスポンスをbooleanに変換
            vm.exists = existsCurrentUserRes.data ? true : false;
        }
    }
</script>
