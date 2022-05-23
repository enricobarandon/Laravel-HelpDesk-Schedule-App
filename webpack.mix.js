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

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps()
    .js([
        //backend js
        'public/js/jquery.min.js',
        'public/js/app.js',
        'public/js/bootstrap.min.js',
        'public/plugins/bootstrap/js/bootstrap.bundle.min.js',
        'public/dist/js/adminlte.min.js',
        // 'public/js/script.js',
    ], 'public/js/min/backend.min.js')
    .styles([
        'public/css/app.css',
        'public/css/app2.css',
        'public/css/bootstrap.min.css',
        'public/css/style.css',
        'public/css/jquery-ui.css',
        // 'public/plugins/fontawesome-free/css/all.min.css',
        'public/dist/css/adminlte.min.css',
   ], 'public/css/min/backend.min.css')
    .styles([
        //login css
        'public/css/bootstrap.min.css',
        'public/dist/css/adminlte.min.css',
        'public/css/app.css',
        'public/css/style.css'
   ], 'public/css/min/login.min.css');