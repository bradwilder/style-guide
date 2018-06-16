var gulp = require('gulp');
var mamp = require('gulp-mamp');
var path = require('path');
var fs = require('fs');

var projectDir = path.resolve(__dirname,  '../../');

// IMPORTANT NOTE: for this to work, I had to hack the httpd.conf-template file inside the gulp-mamp directory.
// Specifically, I had to change line 135 so it matched the line that was produced when I started MAMP manually, which will create /Applications/MAMP/conf/apache/httpd.conf.
// The line I used was:
// LoadModule php7_module        /Applications/MAMP/bin/php/php7.1.1/modules/libphp7.so

var confFile = path.resolve(projectDir, 'node_modules/gulp-mamp/httpd.conf-template');
var content = fs.readFileSync(confFile, 'utf8');
var replacement = content.replace(/LoadModule php\d_module.*/g, 'LoadModule php7_module        /Applications/MAMP/bin/php/php7.1.1/modules/libphp7.so');
fs.writeFileSync(confFile, replacement, 'utf8');

var options =
{
	user: 'bradwilder',
	port: 8888
};

gulp.task('configDev', function(cb)
{
	options.site_path = path.resolve(projectDir, 'app');
	mamp(options, 'config', cb);
});

gulp.task('configDist', function(cb)
{
	options.site_path = path.resolve(projectDir, 'docs');
	mamp(options, 'config', cb);
});

gulp.task('mampStart', function(cb)
{
	mamp(options, 'start', cb);
});

gulp.task('mampStop', function(cb)
{
	mamp(options, 'stop', cb);
});

gulp.task('mampDev', ['configDev'], function()
{
	gulp.start('mampStart');
});

gulp.task('mampDist', ['configDist'], function()
{
	gulp.start('mampStart');
});
