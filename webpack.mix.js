const mix = require('laravel-mix');

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

/*mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');*/

const postCSSPlugins = [
 require('postcss-import'),
 require('postcss-mixins'),
 require('postcss-simple-vars'),
 require('postcss-nested'),
 require('postcss-hexrgba'),
 require('autoprefixer')
]

let cssConfig = {
				test:/\.css$/i,
				use:[{loader:'postcss-loader',options:{plugins:postCSSPlugins}}]
			}

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css')
    .webpackConfig({
        module: {
            rules: [
                cssConfig
            ]
        }
    })
