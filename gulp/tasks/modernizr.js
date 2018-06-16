var gulp = require('gulp');
var modernizr = require('gulp-modernizr');
var del = require('del');

gulp.task('deleteTempScriptsFolder', function()
{
	return del('./app/temp/scripts');
});

gulp.task('modernizr', ['styles', 'deleteTempScriptsFolder'], function()
{
	return gulp.src(['./app/assets/styles/**/*.css', './app/assets/scripts/**/*.js'])
		.pipe(modernizr({"options": ["setClasses"]}))
		.pipe(gulp.dest('./app/temp/scripts/'));
});