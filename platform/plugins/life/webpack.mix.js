let mix = require('laravel-mix');

const publicPath = 'public/vendor/core/plugins/life';
const resourcePath = './platform/plugins/life';

mix
    .js(resourcePath + '/resources/assets/js/jscolor.js', publicPath + '/js')
    .copy(publicPath + '/js/jscolor.js', resourcePath + '/public/js');