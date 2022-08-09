const { src, dest, series } = require('gulp');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const minifyCss = require('gulp-minify-css');
const terser = require('gulp-terser');
const cleanCSS = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');

function css() {
    return src([
        'public/css/bootstrap.min.css',
        'public/css/all.css',
        'public/css/main.css',
        'public/css/import.css',
        'public/css/custom.css',
        'public/css/new.css',
        'public/css/game.css',
        'public/css/rateit.css',
        'public/css/result.css',
        'public/plugins/mmenu-light-master/dist/mmenu-light.css',
    ])
        .pipe(concat('all_minify.min.css'))
        .pipe(sourcemaps.init())
        .pipe(cleanCSS())
        .pipe(minifyCss())
        .pipe(dest('public/css'));
}

function js() {
    return src([
        'public/js/jquery-ui.min.js',
        'public/js/bootstrap.js',
        'public/plugins/mmenu-light-master/dist/mmenu-light.js',
        'public/js/jquery.rateit.min.js',
        'public/js/socket.io.min.js',
        'public/js/custom.js'
    ])
        .pipe(concat('all_minify.min.js'))
        .pipe(terser())
        .pipe(dest('public/js/'));
}

exports.default = series(css, js);
