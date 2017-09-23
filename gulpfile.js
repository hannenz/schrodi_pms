/**
 * Default gulpfile for HALMA projects
 * 
 * Version 2017-07-26
 * 
 * @see: https://www.sitepoint.com/introduction-gulp-js/
 */

'use strict';

var projectName = 'schrodi-pms';

//array of gulp task names that should be included in "gulp build" task
var build = ['js', 'jsvendor', 'css', 'cssvendor', 'img', 'sprites'];


// project settings (enhance if you need to ;-) )
// TODO: move hardcoded values to this settings object
var settings = {
	
	browserSync: {
		proxy:	projectName + '.localhost',
		open: false, // Don't open browser, change to "local" if you want or see https://browsersync.io/docs/options#option-open
		notify: false // Don't notify on every change
	},
	
	css: {
		src:	'./src/css/**/*.scss',
		srcMain: './src/css/main.scss',
		dest:	'./css/',
		options: {
			sass: {
				outputStyle: 'compressed',
				precision: 3,
				errLogToConsole: true,
				includePaths: ['node_modules/bourbon/app/assets/stylesheets/', 'node_modules/bourbon-neat/app/assets/stylesheets/']
			},
			autoprefixer: {
				browsers: ['last 2 versions', '>2%']
			}
		}
	},
	
	js: {
		src:	'./src/js/*.js',
		dest:	'./js/',
		destFile:	'main.min.js'
	},
	
	jsvendor: {
		src:	'./src/js/vendor/*.js',
		dest:	'./js/vendor/',
	},
	
	cssvendor: {
		src:	'./src/css/vendor/**/*.css',
		dest:	'./css/vendor/'
	},
	
	images: {
		src:	'./src/img/**/*',
		dest:	'./img/',
		options: { 
			optimizationLevel: 5
		}
	},

	sprites: {
		team: {
			src:	'./src/team/*.svg',
			dest:	'./img/',
			destFile:	'team.svg',
			optimizationLevel: 5
		},
		icons: {
			src:	'./src/icons/*.svg',
			dest:	'./img/',
			destFile:	'icons.svg',
			optimizationLevel: 5
		}
	},

	sitemap: {
		host:	'http://' + projectName + '.localhost',
		destFile:	'./sitemap.xml'
	},
}




var gulp = require('gulp');
var gutil = require('gulp-util');
var rename = require('gulp-rename');

// CSS => SASS
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var sourcemaps = require('gulp-sourcemaps');

// Image creation
var newer = require('gulp-newer');
var imagemin = require('gulp-imagemin');

// SVG
var svgstore = require('gulp-svgstore');

// Javascript
var concat = require('gulp-concat');
var deporder = require('gulp-deporder');
var stripdebug = require('gulp-strip-debug');
var uglify = require('gulp-uglify');

// Browser synchronisation
var browserSync = require('browser-sync').create();

/*
 *  Task: process SASS and synchronize Browser
 */
gulp.task('css', function () {
	
	return gulp.src(settings.css.srcMain)
		.pipe(sourcemaps.init())
		.pipe(autoprefixer())
		.pipe(sass(settings.css.options.sass).on('error', sass.logError))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest(settings.css.dest))
		.pipe(browserSync.stream())
	;	
});

/*
 * Task: Concat and uglify Javascript
 */
gulp.task('js', function() {

	return gulp.src(settings.js.src)
		// .pipe(deporder())
		.pipe(concat(settings.js.destFile))
		// .pipe(stripdebug())
		.pipe(uglify().on('error', gutil.log))
		.pipe(gulp.dest(settings.js.dest))
		.pipe(browserSync.stream())
	;
});

/*
 * Task: Uglify vendor Javascripts
 */
gulp.task('jsvendor', function() {

	return gulp.src(settings.jsvendor.src)
		// .pipe(deporder())
		// .pipe(stripdebug())
		.pipe(gulp.dest(settings.jsvendor.dest))
	;
});

gulp.task('cssvendor', function() {
	
	return gulp.src(settings.cssvendor.src)
		.pipe(gulp.dest(settings.cssvendor.dest))
	;
})
/*
 * Task: create images
 * TODO: Check if optimization is more effectiv when it is done separately for all different image types (png, svg, jpg)
 */
gulp.task('img', function() {

	// optimize all other images
	// TODO: It seems that plugin in don't overwrites existing files in destination folder!??
	gulp.src(settings.images.src)
		.pipe(newer(settings.images.dest))
		.pipe(imagemin(settings.images.options))
		.pipe(gulp.dest(settings.images.dest))
	;
});

/*
 * Task: create sprites(SVG): optimize and concat SVG icons
 */
gulp.task('sprites', function() {

	for (var p in settings.sprites) {
		gutil.log(p);
		if (!settings.sprites.hasOwnProperty(p)) {
			continue;
		}

		var sprite = settings.sprites[p];
		gulp.src(sprite.src)
			.pipe(imagemin({
				optimizationLevel: sprite.optimizationLevel
			}))
			// .pipe(rename({
			// 	prefix: 'icon-'
			// }))
			.pipe(svgstore({
				inlineSvg: true
			}))
			.pipe(gulp.dest(sprite.dest))
	}
	return;

	for (var p in settings.sprites) {

		if (!settings.sprites.hasOwnProperty(p)) {
			continue;
		} 
		
		var sprite = settings.sprites[p];
		gulp.src(sprite.src)
			.pipe(newer(sprite.dest))
			.pipe(imagemin({
				optimizationLevel: sprite.optimizationLevel
			}))
			.pipe(concat(sprite.destFile))
			.pipe(gulp.dest(sprite.dest))
		;	
	}
});

/*
 * Task: Watch SASS file for changes, build CSS file and reload browser
 */
gulp.task('default', function () {

	browserSync.init(settings.browserSync);

	gulp.watch(settings.css.src, ['css']);
	gulp.watch(settings.js.src, ['js']);
});


/*
 * Task: Build
 */
gulp.task('build', build);

