/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

/**
 * １．resources/js/componentsでVue.jsのコンポーネントを作成
 * ２．以下でVueコンポーネントを登録
 * ３．使いたい場所で<example-component></example-component>のような形で使う
 * コンポーネントからコンポーネントを扱う場合でも、登録する順番は関係ない。
 */

Vue.component('test-component', require('./components/TestComponent.vue').default);
Vue.component('example-component', require('./components/ExampleComponent.vue').default);

// ブログ一覧用コンポーネント
Vue.component('blog-index', require('./components/users/blogs/IndexContent.vue').default);
// Vue.component('blog-index', require('./components/users/blogs/BlogItem'));
// 記事一覧用コンポーネント
Vue.component('article-index', require('./components/users/articles/IndexContent.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
