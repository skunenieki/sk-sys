var elixir = require('laravel-elixir');
require('laravel-elixir-ng-html2js');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.less('app.less')
       .ngHtml2Js('resources/assets/partials/**/*.{htm,html}', 'resources/assets/js')
       .browserify('index.js')
       .version(['css/app.css', 'js/bundle.js'], 'public')
       .copy('node_modules/bootstrap/fonts', 'public/fonts')
       .copy('node_modules/bootstrap/fonts', 'public/build/fonts')
       .copy('node_modules/angular-bootstrap/template', 'public/template')
       .copy('node_modules/angular-bootstrap/template', 'public/build/template');
});
