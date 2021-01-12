<template>
    <span>{{ limitedText }}</span>
</template>

<script>
    export default {
        props:{
            text: String,
            range: Number
        },
        computed:{
            limitedText: function(){
                return this.text.length > this.range ? this.text.substring(0,this.range) + '...' : this.text;
            }
        },
    }
</script>
