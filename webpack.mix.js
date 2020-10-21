const mix = require('laravel-mix');

/*
 *--------------------------------------------------------------------------
 * Mix Asset Management
 *--------------------------------------------------------------------------
 *
 * Mix provides a clean, fluent API for defining some Webpack build steps
 * for your Laravel application. By default, we are compiling the Sass
 * file for the application as well as bundling up all the JS files.
 *
 */

//mix.js('resources/js/app.js', 'public/js');
mix.js([
   'resources/js/app.js'
], 'public/js/app.js');

mix.sass('resources/sass/app.scss', 'public/css')
   .sass('resources/sass/popup.scss', '../resources/build/css')
   .sass('resources/sass/success.scss', '../resources/build/css')
   .sass('resources/sass/articles_show.scss', '../resources/build/css')
   .sass('resources/sass/admins_users_user_card.scss', '../resources/build/css');

mix.styles([
   'resources/build/css/popup.css',
   'resources/build/css/success.css',
   'resources/build/css/articles_show.css',
   'resources/build/css/admins_users_user_card.css',
], 'public/css/all.css');