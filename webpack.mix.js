const { mix } = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .styles([
       'resources/assets/bootstrap/css/bootstrap.min.css',
       'resources/assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css',
       'resources/assets/plugins/select2/select2.min.css',
       'resources/assets/css/AdminLTE.min.css',
       'resources/assets/css/skins/skin-blue.min.css',
       'resources/assets/plugins/iCheck/square/blue.css'
   ], 'public/css/admin.dashboard.css')
   .copyDirectory('resources/assets/img', 'public/img')
   .copyDirectory('resources/assets/plugins/rythm', 'public/js')
   .copy('resources/assets/plugins/iCheck/square/blue.png', 'public/css')
   .copy('resources/assets/plugins/select2/select2.full.min.js', 'public/js')
   .copy('resources/assets/plugins/datatables/jquery.dataTables.min.js', 'public/js')
   .copy('resources/assets/plugins/datatables/dataTables.bootstrap.min.js', 'public/js')
   .copy('resources/assets/plugins/iCheck/icheck.min.js', 'public/js')
   .copy('resources/assets/plugins/flot/jquery.flot.min.js', 'public/js')
   .copy('resources/assets/plugins/flot/jquery.flot.resize.min.js', 'public/js')
   .copy('resources/assets/plugins/jQuery/jquery-2.2.3.min.js', 'public/js')
   .copy('resources/assets/bootstrap/js/bootstrap.min.js', 'public/js')
   .copy('resources/assets/plugins/fastclick/fastclick.js', 'public/js')
   .copy('resources/assets/js/app.min.js', 'public/js')
   .copy('resources/assets/plugins/sparkline/jquery.sparkline.min.js', 'public/js')
   .copy('resources/assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js', 'public/js')
   .copy('resources/assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js', 'public/js')
   .copy('resources/assets/plugins/slimScroll/jquery.slimscroll.min.js', 'public/js')
   .copy('resources/assets/plugins/chartjs/Chart.min.js', 'public/js')
   .copy('resources/assets/js/pages/dashboard2.js', 'public/js')
   .copy('resources/assets/js/demo.js', 'public/js');
