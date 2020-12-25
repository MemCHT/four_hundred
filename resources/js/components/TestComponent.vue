<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" v-for="blog in blogs" :key="blog.id">
                    <div class="card-header">Test Component</div>

                    <div class="card-body">
                        I'm an test component.
                    </div>

                    <div class="blog-test-body">

                        <div>
                            <h1>{{ blog['title'] }}</h1>
                            <p>{{ blog['overview'] }}</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                blogs: [{}],
            };
        },
        created(){
            let vm = this;

            fetch('http://localhost/api/blogs?offset=0&limit=4&sort=newest')
                .then(async res => {

                    const blogs = await res.json();
                    vm.blogs = blogs;
                    console.log(blogs);

                    /**
                     * この後に、
                     * １．articleやuserをblogと紐づけてfetchを送り、ステートとして保持
                     * ２．favoriteやcommentなど外部のコンポーネントを作成＆インポートして表示（この際articleプロパティが必要になってくるかも）
                     * の流れで実装できそう。
                     */
                });
        }
    }
</script>
