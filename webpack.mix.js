let mix = require('laravel-mix');

mix
  .setPublicPath('dist')
  .js('resources/js/multi-select-field.js', 'js')
  .sass('resources/sass/multi-select-field.scss', 'css');
