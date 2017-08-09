let mix = require('laravel-mix');

mix.setPublicPath('public/')
   .js('resources/assets/js/index.js', 'js/')
   .less('resources/assets/less/app.less', 'css/')
   .copy('node_modules/bootstrap/fonts', 'public/fonts')
   .copy('resources/assets/partials', 'public/partials')
   .version()
   .disableNotifications();
