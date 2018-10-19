/**
 * @file
 * Gulp file for building the app.
 */

var gulp = require('gulp');
var babel = require('gulp-babel');
var babelify = require('babelify');
var browserify = require('browserify');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');
var uglify = require('gulp-uglify');
var watch = require('gulp-watch');
var del = require('del');
var sourcemaps = require('gulp-sourcemaps')

/**
 * Deletes the existing output.
 */
gulp.task('clean', function(){
  return del(['./build', './tmp']);
});

/**
 * Browserify and Uglify the Babel output for production use.
 */
gulp.task('build',['clean'], function(){
  process.env.NODE_ENV = 'production';

  const browserifyOptions = {
    entries: ['./src/app.js'],
    fullPaths: false
  }
  const babelifyOptions = {
    presets: ['es2015', 'react'],
    plugins: ['babel-plugin-transform-object-rest-spread']
  }
  const stream = browserify(browserifyOptions)
    .transform(babelify.configure(babelifyOptions))

  return stream.bundle()
    .pipe(source('app.js'))
    .pipe(buffer())
    .pipe(uglify())
    .pipe(gulp.dest('./build'))
});

/**
 * Browserify the Babel output for development.
 */
gulp.task('build-dev', ['clean'], function () {
  const browserifyOptions = {
    entries: ['./src/app.js'],
    debug: true,
    fullPaths: false
  }
  const babelifyOptions = {
    presets: ['es2015', 'react'],
    plugins: ['babel-plugin-transform-object-rest-spread']
  }
  const stream = browserify(browserifyOptions)
    .transform(babelify.configure(babelifyOptions))

  return stream.bundle()
    .pipe(source('app.js'))
    .pipe(buffer())
    .pipe(sourcemaps.init({ loadMaps: true }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./build'))
})

/**
 * Setup a watcher to automatically build the source for development.
 */
gulp.task('watch', function () {
    gulp.watch(['./src/**/*.js'], ['build-dev']);
});
