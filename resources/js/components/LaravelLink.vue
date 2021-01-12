<template>
    <a v-if="blog.user" class="row col-md-12 text-dark m-0 pr-2 pl-2 mb-2" href=""></a>
</template>

<script>
export default {
    props:{
        route: String,
        params: [String],
        id: { type: String, required: false, default:null },
        class: { type: String, required: false, default:null },
        style: { type: String, required: false, default:null },
    },
    methods:{
        /*route: (route, params)=>{
            const paramsLength = params.length;
            const route = '';
            switch(paramsLength){
                case 0:
                    route = 'route()'
            }
        }*/
    }
}
</script>
