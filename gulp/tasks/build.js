var gulp = require('gulp');
var del = require('del');
var usemin = require('gulp-usemin');
var rev = require('gulp-rev');
var cssnano = require('gulp-cssnano');
var uglify = require('gulp-uglify');
var browserSync = require('browser-sync').create();

gulp.task('previewDist', function()
{
	browserSync.init(
	{
		notify: false,
		proxy: "http://localhost:8888"
	});
});

gulp.task('deleteDistFolder', function()
{
	return del('./docs');
});

gulp.task('copyGeneralFiles', function()
{
	var pathsToCopy =
	[
		'./app/**/*',
		'!./app/assets/php/View/Template/Page-header.template.php',
		'!./app/assets/php/View/Template/Page-footer--*.template.php',
		'!./app/assets/styles/**',
		'!./app/assets/scripts/**',
		'!./app/temp',
		'!./app/temp/**'
	];
	return gulp.src(pathsToCopy, {dot: true})
		.pipe(gulp.dest('./docs'));
});

gulp.task('useminTrigger', function()
{
	gulp.start('usemin');
});

gulp.task('usemin', ['scripts'], function()
{
	return gulp.src(['./app/assets/php/View/Template/Page-header.template.php', './app/assets/php/View/Template/Page-footer--*.template.php'])
		.pipe(usemin(
		{
			css: [function() {return rev()}, function() {return cssnano()}],
			js: [function() {return rev()}, function() {return uglify()}]
		}))
		.pipe(gulp.dest('./docs/assets/php/View/Template'));
});

gulp.task('build', ['deleteDistFolder'], function()
{
	gulp.start('do-build');
});

gulp.task('do-build', ['copyGeneralFiles', 'useminTrigger']);