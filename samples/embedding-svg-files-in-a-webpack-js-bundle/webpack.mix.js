/*
 * Fix for the error
 * https://github.com/JeffreyWay/laravel-mix/issues/504
 */
const mix = require('laravel-mix'),
    path = require('path');

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

mix.setPublicPath('./public');
mix.webpackConfig({
    resolveLoader: {
        alias: {
            svgSprite: path.resolve('./webpack/svgSpriteLoader.js'),
        }
    },
});

const jsPath = './js';
const appModules = [
    'app',
];

// standard modules
appModules.forEach((moduleName) => {
    mix.js(`${jsPath}/${moduleName}.js`, 'js');
});
