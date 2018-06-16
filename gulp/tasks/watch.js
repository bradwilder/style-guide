var gulp = require('gulp');
var watch = require('gulp-watch');
var browserSync = require('browser-sync').create();
var phpunit = require('gulp-phpunit');

gulp.task('watch', function()
{
	browserSync.init(
	{
		notify: false,
		proxy: "http://localhost:8888"
	});
	
	watch(['./app/index.php', 'app/assets/php/**/*.php'], function()
	{
		browserSync.reload();
		
		var options =
		{
			bootstrap: "./vendor/autoload.php",
			includePath: "./app/assets/php/Tests/*"
		};
		gulp.src('./app/assets/php/Tests/phpunit.xml')
			.pipe(phpunit('./vendor/bin/phpunit', options).on('error', function() {}));
	});
	
	watch(['./app/assets/styles/**/*.less', './app/assets/styles/**/*.css', '!./app/assets/styles/compiledCSS/**/*.css'], function()
	{
		gulp.start('cssInject');
	});
	
	watch('./app/assets/scripts/**/*.js', function()
	{
		gulp.start('scriptsRefresh');
	});
});

gulp.task('cssInject', ['styles'], function()
{
	return gulp.src('./app/temp/styles/styles.css')
		.pipe(browserSync.stream());
});

gulp.task('scriptsRefresh', ['scripts'], function()
{
	browserSync.reload();
});
