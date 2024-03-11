var gulp = require('gulp');
var sass = require('gulp-sass')(require('sass'));
var autoprefixer = require('gulp-autoprefixer');

// Source files
let components = 'components';

// Building scss.
gulp.task('scss', () => {
  return gulp.src(components + '/**/*.scss', { base: components })
    .pipe(sass.sync({}).on('error', sass.logError))
    .pipe(autoprefixer())
    .pipe(gulp.dest(function(file) {
      return file.base;
    }));
});

// Build task: 'gulp build'
gulp.task('build', gulp.series('scss'));
// Build task: 'gulp watch'
gulp.task('watch', function(){
  return gulp.watch(components + '/**/*.scss', gulp.series('scss'));
});

// Default task: 'gulp'
gulp.task('default', gulp.series('build', 'watch'));
