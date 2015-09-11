'use strict';

var changed = require('gulp-changed');
var gulp = require('gulp');
var imagemin = require('gulp-imagemin');

var inputDir = './src';
var dest = './dest/img';

gulp.task('images', function () {

    return gulp.src(inputDir + '/img/**')
        .pipe(changed(dest)) // Ignore unchanged files
        .pipe(imagemin()) // Optimize
        .pipe(gulp.dest(dest));
});
