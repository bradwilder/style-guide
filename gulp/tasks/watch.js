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
	
	var phpunitOptions =
	{
		bootstrap: "./vendor/autoload.php",
		includePath: "./app/assets/php/Tests/*",
		configurationFile: './app/assets/php/Tests/phpunit.xml'
	};
	
	watch(['./app/index.php', 'app/assets/php/**/*.php'], function()
	{
		browserSync.reload();
	});
	
	watch(['app/assets/php/DB/**/*.php', 'app/assets/php/DataClasses/**/*.php', 'app/assets/php/Tests/DataClasses/**/*.php'], function()
	{
		let options = Object.assign(phpunitOptions);
		options.testSuite = 'DataClasses';
		
		gulp.src('').pipe(phpunit('./vendor/bin/phpunit', options).on('error', function() {}));
	});
	
	watch(['app/assets/php/phpauth/**/*.php', 'app/assets/php/Tests/phpauth/**/*.php'], function()
	{
		let options = Object.assign(phpunitOptions);
		options.testSuite = 'Auth';
		
		gulp.src('').pipe(phpunit('./vendor/bin/phpunit', options).on('error', function() {}));
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
