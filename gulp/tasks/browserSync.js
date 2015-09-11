'use strict';

var browserSync = require('browser-sync');
var gulp        = require('gulp');

var outputDir = 'dest';

gulp.task('browserSync', ['build'], function() {
    browserSync.init([outputDir + "/css/**", outputDir + "/js/**", "views/**"], {
        //server: {
        //    baseDir: 'public'
        //}
        //host: "mt.dev",
        //proxy: '192.168.33.20'
        proxy: '127.0.0.1:8080'
    });
});
