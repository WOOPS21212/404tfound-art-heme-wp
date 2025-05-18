 
const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const browserSync = require('browser-sync').create();
// Temporarily remove autoprefixer to fix compilation

// Set your local development URL
const localSiteURL = 'http://404found-art.local';

// Compile SCSS
function style() {
  return gulp.src('./sass/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    // Temporarily remove autoprefixer
    .pipe(gulp.dest('./'))
    .pipe(browserSync.stream());
}

// Watch function
function watch() {
  browserSync.init({
    proxy: localSiteURL,
    notify: false
  });
  
  gulp.watch('./sass/**/*.scss', style);
  gulp.watch('./**/*.php').on('change', browserSync.reload);
  gulp.watch('./js/**/*.js').on('change', browserSync.reload);
}

exports.style = style;
exports.watch = watch;
exports.default = watch;
