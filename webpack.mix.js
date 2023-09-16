let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js([
    'resources/assets/js/app.js',
    'resources/assets/js/socket.js',
],
'public/js');

mix.scripts([
    //'node_modules/socket.io-client/dist/socket.io.js',
    //'node_modules/vue/dist/vue.js',
    'public/js/app.js',
    'public/js/socket.js'
], 'public/js/all.js');
