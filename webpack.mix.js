const mix = require('laravel-mix');
const webpack = require('webpack');
const webpackPluginS3 = require('webpack-s3-plugin');
// const commitHash = require('child_process').execSync('git rev-parse --short HEAD').toString().trim();

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
let webpackPlugins = [

    // new webpack.DefinePlugin({
    //     __COMMIT_HASH__: JSON.stringify(commitHash),
    // }),

    new webpack.ProvidePlugin({
        Raphael: 'raphael',
        '$': 'jquery',
        'jQuery': 'jquery',
        'window.jQuery': 'jquery',
    }),

];
const upload = (process.env.UPLOAD_S3 === 'true');

mix.js('resources/js/app.js', 'public/dashboard/js/vendors.js')
    .js([
        'resources/admin-assets/js/core/app-menu.js',
        'resources/admin-assets/js/core/app.js',
    ], 'public/dashboard/js/stack.js')
    .scripts([
        'public/admin/js/custom.js'
    ],'public/dashboard/js/custom.js')
    .js('resources/js/tikshif/patients/show.js', 'public/dashboard/js/patient.js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/dashboard/app.scss', 'public/dashboard/css/app.css')
    .sass('resources/sass/dashboard/ltr-app.scss', 'public/dashboard/css/ltr-app.css')
    .sass('resources/sass/dashboard/rtl-app.scss', 'public/dashboard/css/rtl-app.css')
;


mix.scripts([
    'resources/tikshif/js/jquery-3.5.1.min.js',
    'resources/tikshif/js/plugins.js',
    'resources/tikshif/js/appear.min.js',
    'resources/tikshif/js/odometer.min.js',
    'resources/tikshif/js/main.js'
], 'public/portal/js/app.js')
    .sass('resources/sass/tikshif/app.scss','public/portal/css/app.css')
    .sass('resources/sass/tikshif/app-rtl.scss','public/portal/css/app-rtl.css');


mix.scripts([
    'public/landing/assets/js/vendors/jquery-3.5.1.min.js',
    'public/landing/assets/js/vendors/popper.min.js',
    'public/landing/assets/js/vendors/bootstrap.min.js',
    'public/landing/assets/js/vendors/jquery.easing.min.js',
    'public/landing/assets/js/vendors/owl.carousel.min.js',
    'public/landing/assets/js/vendors/countdown.min.js',
    'public/landing/assets/js/vendors/jquery.waypoints.min.js',
    'public/landing/assets/js/vendors/jquery.rcounterup.js',
    'public/landing/assets/js/vendors/magnific-popup.min.js',
    'public/landing/assets/js/vendors/validator.min.js',
], 'public/landing/compiled/js/vendors.js')
    .scripts([
    'public/landing/assets/js/app-ar.js',
    ], 'public/landing/compiled/js/app-ar.js').scripts([
    'public/landing/assets/js/app.js',
    ], 'public/landing/compiled/js/app-en.js')
    .sass('public/landing/assets/css/main.scss','public/landing/compiled/css/main-en.css')
    .sass('public/landing/assets/css/main-rtl.scss','public/landing/compiled/css/main-ar.css');


/**
 * Upload static assets to S3
 */
if (upload) {
    console.log('UPLOAD ON S3: ' + upload);
    console.log('UPLOAD To BUCKET: ' + process.env.S3_BUCKET);
    mix.webpackConfig({
        plugins: [
            new webpackPluginS3({
                s3Options: {
                    accessKeyId: process.env.AWS_ACCESS_KEY_ID,
                    secretAccessKey: process.env.AWS_SECRET_ACCESS_KEY,
                    region: 'us-east-1',
                },
                include: [
                    /.*\.(css|js|br|gz|svg|png|gif|jpg|eot|ttf|woff|woff2)/,
                ],
                exclude: [
                    /.*\.(php|htaccess)/,
                    'storage/'
                ],
                s3UploadOptions: {
                    Bucket: process.env.S3_BUCKET
                },
                directory: 'public'
            })
        ]
    });
}
