const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

elixir((mix) => {
    mix.sass('app.scss', 'resources/assets/css')
    .sass('admin.scss', 'resources/assets/css')
    .styles([
        'app.css', '../vendor/datatables.net-bs/css/dataTables.bootstrap.css'
    ], 'public/css/app.css')
    .styles([
    'admin.css', 'theme.css'
    ], 'public/css/admin.css')
    .scripts([
        '../vendor/jquery/dist/jquery.js',
        '../vendor/bootstrap-sass/assets/javascripts/bootstrap.js',
        '../vendor/select2/dist/js/select2.js',
        '../vendor/handlebars/handlebars.js',
        '../vendor/ladda-bootstrap/dist/spin.js',
        '../vendor/ladda-bootstrap/dist/ladda.js',
        '../vendor/numeral/src/numeral.js',
        '../vendor/moment/moment.js',
        '../vendor/moment/locale/ko.js',
        '../vendor/eonasdan-bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js',
        '../vendor/cleave.js/dist/cleave.js',
        '../vendor/datatables.net/js/jquery.dataTables.js',
        '../vendor/datatables.net-bs/js/dataTables.bootstrap.js',
        'app.js'
    ], 'public/js/app.js')
    .version([
        'css/app.css',
        'css/admin.css',
        'js/app.js'
    ])
    .copy("resources/assets/vendor/font-awesome/fonts", 'public/build/fonts')
    .copy("../vendor/bootstrap-sass/assets/fonts/bootstrap", 'public/build/fonts');

});
