<template>
    <div class="text-center mb-4">
        <button class="btn btn-primary col-md-4" v-on:click="seeMoreAction">もっと見る</button>
    </div>
</template>

<script>
    export default {
        props:{
            seeMoreAction: Function
        },
    }
</script>
