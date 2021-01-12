<template>
    <div class="d-flex align-items-center">
        <div>
        <img :src="'/images/icon/'+user.icon" alt="user_icon" :style="'width: ' + (width ? width : '40px') ">
        </div>
        <div class="ml-3">
            <p class="mb-0"><strong>{{ user.name }}</strong></p>
            <p class="mb-0"><small>{{ subInfo }}</small></p>
        </div>
    </div>

</template>

<script>
    export default {
        props:{
            user: Object,
            subInfo: String,
            width: String
        }
    }
</script>
