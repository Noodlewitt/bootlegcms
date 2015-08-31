var gulp = require('gulp');
var compass = require('gulp-compass');
var shell = require('gulp-shell');
var uglify = require('gulp-uglifyjs');

gulp.task('default', function() {
  // place code for your default task here
});

gulp.task('compass', function() {
    gulp.src('./public/sass/*.scss')
        .pipe(compass({
            css: 'public/css',
            sass: 'public/sass',
            image: 'public/images'
        }))
        .pipe(shell([
            'php ../../../artisan vendor:publish'
        ]));
});

gulp.task('uglify', function() {
    gulp.src([
        'public/components/jquery/jquery.js',
        'public/components/jquery-ui/ui/jquery-ui.js',
        'public/components/bootstrap-sass/assets/javascripts/bootstrap/alert.js',
        'public/components/bootstrap-sass/assets/javascripts/bootstrap/modal.js',
        'public/components/bootstrap-sass/assets/javascripts/bootstrap/tab.js',
        'public/components/bootstrap-sass/assets/javascripts/bootstrap/dropdown.js',
        'public/components/bootstrap-sass/assets/javascripts/bootstrap/collapse.js',
        'public/components/bootstrap-sass/assets/javascripts/bootstrap/transition.js',
        'public/components/jstree/dist/jstree.js',
        'public/components/blueimp-tmpl/js/tmpl.js',
        'public/components/blueimp-load-image/js/load-image.all.min.js',
        'public/components/blueimp-canvas-to-blob/js/canvas-to-blob.min.js',
        'public/components/blueimp-file-upload/js/jquery.iframe-transport.js',
        'public/components/blueimp-file-upload/js/jquery.fileupload.js',
        'public/components/blueimp-file-upload/js/jquery.fileupload-process.js',
        'public/components/blueimp-file-upload/js/jquery.fileupload-image.js',
        'public/components/blueimp-file-upload/js/jquery.fileupload-audio.js',
        'public/components/blueimp-file-upload/js/jquery.fileupload-video.js',
        'public/components/blueimp-file-upload/js/jquery.fileupload-validate.js',
        'public/components/blueimp-file-upload/js/jquery.fileupload-ui.js',
        'public/components/tinymce-builded/js/tinymce/tinymce.jquery.js',
        'public/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.js',
        'public/components/bootstrap-sass-datepicker/js/bootstrap-sass-datepicker.js',
        'public/components/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js',
        'public/components/select2/dist/js/select2.full.js',
        'public/components/js-cookie/src/js.cookie.js'
        ])
        .pipe(uglify('script.min.js', {
            mangle: false,
            outSourceMap: true,
            output: {
                beautify: false
            }
        }))
        .pipe(gulp.dest('public/js'))
        .pipe(shell([
            'php ../../../artisan vendor:publish'
        ]));
});


gulp.task('watch', function() {
    gulp.watch('./public/sass/**/*.scss', ['compass', ]);
});
