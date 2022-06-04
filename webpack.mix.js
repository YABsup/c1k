const mix = require('laravel-mix');
mix.setPublicPath('public/');

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

let fs = require('fs');

let getFiles = function (dir) {
    return fs.readdirSync(dir).filter(file => {
        return fs.statSync(`${dir}/${file}`).isFile();
    });
};

getFiles('resources/js/').forEach(function (filepath) {
    mix.js('resources/js/' + filepath, 'public/js').version();
});

getFiles('resources/sass/').forEach(function (filepath) {
    mix.sass('resources/sass/' + filepath, 'public/css').version();
});

getFiles('resources/js/bootstrap/').forEach(function (filepath) {
    mix.js('resources/js/bootsrap/' + filepath, 'public/js/bootstrap').version();
});





/* mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/admin.js', 'public/js')
    .sass(['resources/sass/*.scss'], 'public/css')
    .js('resources/js/c1k/bootstrap.min.js', 'public/js/c1k')
    .js('resources/js/c1k/feeded.js', 'public/js/c1k')
    .js('resources/js/c1k/rates.js', 'public/js/c1k')
    .js('resources/js/c1k/login.js', 'public/js/c1k')
    .js('resources/js/c1k/main.js', 'public/js/c1k')
    .js('resources/js/c1k/news.js', 'public/js/c1k')
    .js('resources/js/c1k/sign_up.js', 'public/js/c1k')
    .js('resources/js/c1k/slick.js', 'public/js/c1k')
    .js('resources/js/c1k/slick.min.js', 'public/js/c1k')
    .js('resources/js/c1k/xchange.js', 'public/js/c1k')
    .js('resources/js/c1k/email-decode.min.js', 'public/js/c1k');

*/