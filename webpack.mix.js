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

//
// Watch config
//

if (process.env.WATCH === 'all') {
    mix.js('resources/js/vendor.js', 'public/js')
        .js('resources/js/app.js', 'public/js')
        .sass('resources/sass/vendor.scss', 'public/css')
        .sass('resources/sass/layout_default.scss', 'public/css')
        .sass('resources/sass/layout_error.scss', 'public/css')
        .sourceMaps();
}
// Local: compile only app assets (without large vendor ones)
// Used only for watching, to speed up building process
else if (process.env.WATCH === 'app') {
    mix.js('resources/js/app.js', 'public/js')
        .sass('resources/sass/layout_default.scss', 'public/css')
        .sass('resources/sass/layout_error.scss', 'public/css')
        .sourceMaps();
}

//
// Build config
//

if (typeof process.env.WATCH === 'undefined') {
    // Development: Compile all assets, non minified, without file versions
    if (process.env.NODE_ENV === 'development') {
        mix.js('resources/js/vendor.js', 'public/js')
            .js('resources/js/app.js', 'public/js')
            .sass('resources/sass/vendor.scss', 'public/css')
            .sass('resources/sass/layout_default.scss', 'public/css')
            .sass('resources/sass/layout_error.scss', 'public/css')
            .sourceMaps();
    }
    // Production: compile all assets with versions, minified
    else if (process.env.NODE_ENV == 'production') {
        mix.js('resources/js/vendor.js', 'public/js')
            .js('resources/js/app.js', 'public/js')
            .sass('resources/sass/vendor.scss', 'public/css')
            .sass('resources/sass/layout_default.scss', 'public/css')
            .sass('resources/sass/layout_error.scss', 'public/css')
            .version();
    }
}
