var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir.config.sourcemaps = false;

elixir(function(mix) {
    mix.sass([
        'app.scss'
    ], '../assets/css');
});

elixir(function(mix) {
    mix.copy('resources/assets/fonts', '../assets/fonts');
    mix.copy('resources/assets/images', '../assets/images');
    mix.copy('resources/assets/js', '../assets/js');

    mix.copy('node_modules/bootstrap-sass/assets/fonts/bootstrap', '../assets/fonts');
    mix.copy('node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js', '../assets/js');
});