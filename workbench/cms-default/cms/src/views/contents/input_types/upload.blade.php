<?php
$niceName = preg_replace('/\s+/', '', $setting->name);
if(@$setting->value){
    $url = $setting->value;

    $fileName = pathinfo($url,PATHINFO_FILENAME);
    $fileObj = new stdClass();
    $fileObj->name = $fileName;
    $fileObj->thumbnailUrl = "$url"; //todo
    $fileObj->deleteUrl = action('ContentsController@deleteUpload', array('id'=>$setting->id)); //todo
    $fileObj->deleteType = "DELETE";

    $files[] = $fileObj;
    //TODO: handle multiple files here?
    $files = json_encode($files);   
}
   
?>
<div class="wrap">
    <div class='upload {{$niceName}}' >    
        {{ Form::label('setting['.$setting->name.']', ucfirst($setting->name).':') }}
        {{ Form::hidden('setting['.$setting->name.']', $setting->value, array('class'=>'form-control file-url')) }}


            <!-- Redirect browsers with JavaScript disabled to the origin page -->
            <noscript><input type="hidden" name="redirect" value="http://blueimp.github.io/jQuery-File-Upload/"></noscript>
            <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
            <div class="row fileupload-buttonbar">
                <div class="col-lg-7">
                    <!-- The fileinput-button span is used to style the file input field as button -->
                    <span class="btn btn-success fileinput-button">
                        <i class="glyphicon glyphicon-plus"></i>
                        <span>Add files...</span>
                        <input type="file" name="{{$niceName}}[]" multiple>
                    </span>
                    <button type="submit" class="btn btn-primary start">
                        <i class="glyphicon glyphicon-upload"></i>
                        <span>Start upload</span>
                    </button>
                    <button type="reset" class="btn btn-warning cancel">
                        <i class="glyphicon glyphicon-ban-circle"></i>
                        <span>Cancel upload</span>
                    </button>
                    <button type="button" class="btn btn-danger delete">
                        <i class="glyphicon glyphicon-trash"></i>
                        <span>Delete</span>
                    </button>
                    <input type="checkbox" class="toggle">
                    <!-- The global file processing state -->
                    <span class="fileupload-process"></span>
                </div>
                <!-- The global progress state -->
                <div class="col-lg-5 fileupload-progress fade">
                    <!-- The global progress bar -->
                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                    </div>
                    <!-- The extended global progress state -->
                    <div class="progress-extended">&nbsp;</div>
                </div>
            </div>
            <!-- The table listing the files available for upload/download -->
            <table id="{{uniqid()}}" role="presentation" class="table table-striped uploaded"><tbody class="files"></tbody></table>


            <!-- The template to display files available for upload -->
            <script id="{{uniqid()}}" class='upload-template' type="text/x-tmpl">
            {% for (var i=0, file; file=o.files[i]; i++) { %}
                <tr class="template-upload fade">
                    <td class="preview-wrap">
                        <span class="preview"></span>
                    </td>
                    <td>
                        <p class="name">{%=file.name%}</p>
                        <strong class="error text-danger"></strong>
                    </td>
                    <td>
                        <p class="size">Processing...</p>
                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
                    </td>
                    <td>
                        {% if (!i && !o.options.autoUpload) { %}
                            <button class="btn btn-primary start" disabled>
                                <i class="glyphicon glyphicon-upload"></i>
                                <span>Start</span>
                            </button>
                        {% } %}
                        {% if (!i) { %}
                            <button class="btn btn-warning cancel">
                                <i class="glyphicon glyphicon-ban-circle"></i>
                                <span>Cancel</span>
                            </button>
                        {% } %}
                    </td>
                </tr>
            {% } %}
            </script>
            <!-- The template to display files available for download -->
            <script id="{{uniqid()}}" class='download-template' type="text/x-tmpl">
            {% for (var i=0, file; file=o.files[i]; i++) { %}
                <tr class="template-download fade">
                    <td class="preview-wrap">
                        <span class="preview">
                            {% if (file.thumbnailUrl) { %}
                                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}" alt="preview" class="img-thumbnail"></a>
                            {% } %}
                        </span>
                    </td>
                    <td>
                        <p class="name">
                            {% if (file.url) { %}
                                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                            {% } else { %}
                                <span>{%=file.name%}</span>
                            {% } %}
                        </p>
                        {% if (file.error) { %}
                            <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                        {% } %}
                    </td>
                    <td>
                        <span class="size">{%=o.formatFileSize(file.size)%}</span>
                    </td>
                    <td>
                        {% if (file.deleteUrl) { %}
                            <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                                <i class="glyphicon glyphicon-trash"></i>
                                <span>Delete</span>
                            </button>
                            <input type="checkbox" name="delete" value="1" class="toggle">
                        {% } else { %}
                            <button class="btn btn-warning cancel">
                                <i class="glyphicon glyphicon-ban-circle"></i>
                                <span>Cancel</span>
                            </button>
                        {% } %}
                    </td>
                </tr>
            {% } %}
        </script>

        <script type="text/javascript">

            $(function() {

                var $container{{$niceName}} = $('div.upload.{{$niceName}}');
                var $form{{$niceName}} = $container{{$niceName}}.closest('div.wrap');

                // Initialize the jQuery File Upload widget:
                $form{{$niceName}}.fileupload({
                    // Uncomment the following to send cross-domain cookies:
                    //xhrFields: {withCredentials: true},
                    url: "{{{action('ContentsController@postUpload', array('id'=>$setting->id))}}}",
                    maxNumberOfFiles:1,
                    singleFileUploads:true,
                    limitConcurrentUploads:3,
                    disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
                    maxFileSize: 5000000,
                    acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,

                    fileInput: $('input[type=file]', $container{{$niceName}}),

                    filesContainer: $('.files',$container{{$niceName}}),

                    uploadTemplateId: $('.upload-template', $container{{$niceName}}).attr('id'),

                    downloadTemplateId: $('.download-template', $container{{$niceName}}).attr('id'),

                });

                $form{{$niceName}}.bind('fileuploaddone', function (e, data) {
                    setTimeout(function(){
                        $('input.file-url', $container{{$niceName}}).val($('span.preview img', $container{{$niceName}}).attr('src'));
                    }, 1000);
                }).bind('fileuploaddestroyed', function (e, data) {
                    $('input.file-url', $container{{$niceName}}).val('');
                }); 

                @if(@$files)
                    var files{{$niceName}} = {{$files}};
                    $form{{$niceName}}.fileupload('option', 'done').call($form{{$niceName}}, $.Event('done'), {result: {files: files{{$niceName}} }});
                @endif

                // Enable iframe cross-domain access via redirect option:
                $form{{$niceName}}.fileupload('option', 'redirect',
                window.location.href.replace(/\/[^\/]*$/, '/cors/cors.html?%s'));

                // Upload server status check for browsers with CORS support:
                if ($.support.cors) {
                    $.ajax({
                        url: '//'+window.location.hostname+'/',
                        type: 'HEAD'
                    }).fail(function() {
                        $('<div class="alert alert-danger"/>')
                            .text('Upload server currently unavailable - ' + new Date())
                            .appendTo('form');
                    });
                }


            });

        </script>
    </div>
</div>