var gulp = require('gulp');
var less = require('gulp-less');
var cssImport = require('postcss-import');
var postcss = require('gulp-postcss');
var autoprefixer = require('autoprefixer');
var cssVars = require('postcss-simple-vars');
var nested = require('postcss-nested');
var mixins = require('postcss-mixins');
var hexrgba = require('postcss-hexrgba');
var del = require('del');

gulp.task('deleteTempStylesFolder', function()
{
	return del('./app/temp/styles');
});

gulp.task('cleanCompiledStylesFolder', function()
{
	return del('./app/assets/styles/compiledCSS/**/*');
});

gulp.task('less', ['cleanCompiledStylesFolder'], function()
{
	return gulp.src(['./app/assets/styles/base/*.less', './app/assets/styles/modules/*.less'])
		.pipe(less({paths: ['./node_modules/material-shadows']}))
		.on('error', function(errorInfo)
		{
			console.log(errorInfo.toString());
			this.emit('end');
		})
		.pipe(gulp.dest('./app/assets/styles/compiledCSS'));
});

gulp.task('styles', ['less', 'deleteTempStylesFolder'], function()
{
    return gulp.src('./app/assets/styles/styles.css')
		.pipe(postcss([cssImport, mixins, cssVars, nested, hexrgba, autoprefixer]))
		.on('error', function(errorInfo)
		{
			console.log(errorInfo.toString());
			this.emit('end');
		})
		.pipe(gulp.dest('./app/temp/styles'));
});
