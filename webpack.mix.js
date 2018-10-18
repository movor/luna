let mix = require('laravel-mix');
mix.disableNotifications();

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

// Local: compile only app assets (without large vendor ones)
// Used only for watching, to speed up building process
if (process.env.NODE_ENV === 'local') {
    mix.js('resources/assets/js/app.js', 'public/js')
        .sass('resources/assets/scss/layout_default.scss', 'public/css')
        .sass('resources/assets/scss/layout_error.scss', 'public/css');
}
// Development: Compile all assets, non minified, without file versions
else if (process.env.NODE_ENV === 'development') {
    mix.js('resources/assets/js/vendor.js', 'public/js')
        .js('resources/assets/js/app.js', 'public/js')
        .sass('resources/assets/scss/vendor.scss', 'public/css')
        .sass('resources/assets/scss/layout_default.scss', 'public/css')
        .sass('resources/assets/scss/layout_error.scss', 'public/css');
}
// Production: compile all assets with versions, minified
if (process.env.NODE_ENV == 'production') {
    mix.js('resources/assets/js/vendor.js', 'public/js')
        .js('resources/assets/js/app.js', 'public/js')
        .sass('resources/assets/scss/vendor.scss', 'public/css')
        .sass('resources/assets/scss/layout_default.scss', 'public/css')
        .sass('resources/assets/scss/layout_error.scss', 'public/css')
        .version();
}
