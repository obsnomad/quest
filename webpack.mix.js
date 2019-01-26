const mix = require('laravel-mix');

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

mix.options({
    processCssUrls: false
});

mix.js('resources/assets/js/app.js', 'public/js')
    .react('resources/assets/js/schedule.jsx', 'public/js')
    .react('resources/assets/js/schedule-single.jsx', 'public/js')
    .react('resources/assets/js/certificate.jsx', 'public/js')
    .sass('resources/assets/sass/vk.scss', 'public/css')
    .sass('resources/assets/sass/app.scss', 'public/css');
