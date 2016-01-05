/*

###################
#                 #
#  ASSET OPTIONS  #
#                 #
###################

TYPE -------- OPTION -------- DEFAULT ------ DESCRIPTION
global ------ path ---------- undefined ---- pattern to match file in input folder
css --------- outputs ------- compressed --- output style (compressed, expanded, etc)
css --------- autoprefix ---- true --------- automatically adds vendor prefixes for unsupported css (e.g. -webkit, -moz)
css --------- precision ----- 8 -------------number precision - useful if compiling bootstrap with its line-height var set to 1095702915325423 decimal places
css --------- sourcemaps ---- true --------- publish sourcemap?
css --------- min_suffix ---- true --------- add .min suffix to minifed files?
css --------- show_size ----- true --------- show file size in console log (minified only)
js ---------- sourcemaps ---- true --------- publish sourcemap?
js ---------- min_suffix ---- true --------- add .min suffix to minifed files?
js ---------- show_size ----- true --------- show file size in console log (original + minified)
components -- sourcemaps ---- false -------- publish sourcemap?
components -- min_suffix ---- true --------- add .min suffix to minifed files?
components -- show_size ----- true --------- show file size in console log (original + minified)


###################
#                 #
# PUBLISH OPTIONS #
#                 #
###################

TYPE -------- OPTION -------- DEFAULT ------ DESCRIPTION
global ------ path ---------- undefined ---- pattern to publish files of type
css --------- clean --------- false -------- if true, path will be cleared before publish.
js ---------- clean --------- false -------- if true, path will be cleared before publish.
components -- clean --------- true --------- if true, path will be cleared before publish.

*/

module.exports = {

    //working directory for all sass files, bower components, etc
    assets: {
        folder:             'resources/',
        svg: {
            template:       'resources/scss/fonts/_font.tpl'
        }
    },

    //array of directories that will be published to
    publish: [
        {
            folder:         'public/',
            css: {
                clean:      true,
            },
            js: {
                clean:      true,
            },
            img: {
                clean:      true,
            },
            components: {
                clean:      false,
            }
        }
    ],

    //list all bower components, separated into arrays of packages to be concatenated
    components: {
        scripts: [ //outputs: scripts.min.js
            'resources/components/jquery/dist/jquery.js',
            'resources/components/jquery-ui/ui/jquery-ui.js',
            'resources/components/bootstrap-sass/assets/javascripts/bootstrap.js',
            //'resources/components/bootstrap-sass/assets/javascripts/bootstrap/alert.js',
            //'resources/components/bootstrap-sass/assets/javascripts/bootstrap/modal.js',
            //'resources/components/bootstrap-sass/assets/javascripts/bootstrap/tab.js',
            //'resources/components/bootstrap-sass/assets/javascripts/bootstrap/dropdown.js',
            //'resources/components/bootstrap-sass/assets/javascripts/bootstrap/collapse.js',
            //'resources/components/bootstrap-sass/assets/javascripts/bootstrap/transition.js',
            'resources/components/jstree/dist/jstree.js',
            'resources/components/blueimp-tmpl/js/tmpl.js',
            'resources/components/blueimp-load-image/js/load-image.all.min.js',
            'resources/components/blueimp-canvas-to-blob/js/canvas-to-blob.min.js',
            'resources/components/blueimp-file-upload/js/jquery.iframe-transport.js',
            'resources/components/blueimp-file-upload/js/jquery.fileupload.js',
            'resources/components/blueimp-file-upload/js/jquery.fileupload-process.js',
            'resources/components/blueimp-file-upload/js/jquery.fileupload-image.js',
            'resources/components/blueimp-file-upload/js/jquery.fileupload-audio.js',
            'resources/components/blueimp-file-upload/js/jquery.fileupload-video.js',
            'resources/components/blueimp-file-upload/js/jquery.fileupload-validate.js',
            'resources/components/blueimp-file-upload/js/jquery.fileupload-ui.js',
            'resources/components/tinymce-builded/js/tinymce/tinymce.jquery.js',
            'resources/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.js',
            'resources/components/bootstrap-sass-datepicker/js/bootstrap-sass-datepicker.js',
            'resources/components/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js',
            'resources/components/intro.js/intro.js',

        ]
    }
}
