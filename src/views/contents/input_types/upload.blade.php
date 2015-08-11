<?php
$params = Contentsetting::parseParams($setting[0]);
$niceName = preg_replace('/\s+/', '_', $setting[0]->name);
$field_title = isset($params->field_title) ? $params->field_title : $setting[0]->name;
$files = array();
foreach($setting as $field){
    if(@$field->name){

        $url = $field->value;

        $fileName = pathinfo($url,PATHINFO_FILENAME);
        $fileObj = new stdClass();
        $fileObj->name = $fileName;
        $fileObj->thumbnailUrl = "$url"; //todo
        $fileObj->url = "$url";
        $fileObj->deleteUrl = action('\Bootleg\Cms\ContentsController@deleteUpload', array('id'=>$field->id)); //todo
        $fileObj->deleteType = "DELETE";
        $fileObj->id = $field->id;

        $files[] = $fileObj;
        //TODO: handle multiple files here?
    }
}
$files = json_encode($files);
?>
<div class="wrap">
    <div class='upload {{$niceName}}' >
        @if($setting[0]->name !="_inline")
        {!! Form::label("setting[".$setting[0]->name."][".$setting[0]->id."]", ucfirst($field_title.":")) !!}
        @endif


            <!-- The table listing the files available for upload/download -->
            <table id="{{uniqid()}}" role="presentation" class="table table-striped uploaded"><tbody class="files"></tbody></table>

            <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
            <div class="row fileupload-buttonbar">
                <div class="col-lg-7
                @if($setting[0]->name == "_inline")
                    text-center
                @endif
                ">
                    <div class="btn-group">
                    <!-- The fileinput-button span is used to style the file input field as button -->

                        @if($params->max_number == 1)
                            <span class="btn btn-success fileinput-button">
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Choose file...</span>
                                <input type="file" name="{{$setting[0]->name}}[]" multiple>
                            </span>
                        @else
                            <span class="btn btn-success fileinput-button">
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Add file...</span>
                                <input type="file" name="{{$setting[0]->name}}[]" multiple>
                            </span>
                        @endif
                        {{--
                        <button type="submit" class="btn btn-primary start">
                            <i class="glyphicon glyphicon-upload"></i>
                            <span>Start upload</span>
                        </button>--}}

                        <button type="button" class="btn btn-danger delete">
                            <i class="glyphicon glyphicon-trash"></i>
                            <span>Delete</span>
                        </button>
                    </div>
                    @if($setting[0]->name !="_inline")
                        <input type="checkbox" class="toggle">
                    @endif
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


            <!-- The template to display files available for upload -->
            <script id="{{uniqid()}}" class='upload-template' type="text/x-tmpl">
            {% for (var i=0, file; file=o.files[i]; i++) { %}
                <tr class="template-upload fade">
                    <td class="vertical-middle preview-wrap">
                        <span class="preview"></span>
                    </td>
                    <td class='vertical-middle'>
                        <p class="name">{%=file.name%}</p>
                        <strong class="error text-danger"></strong>
                    </td>
                    <td class='vertical-middle'>
                        <p class="size">Processing...</p>
                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
                    </td>
                    <td class='vertical-middle'>
                        {% if (!i && !o.options.autoUpload) { %}
                            <button class="btn btn-primary start" disabled>
                                <i class="glyphicon glyphicon-upload"></i>
                                <span>Start</span>
                            </button>
                        {% } %}
                        @if($setting[0]->name !="_inline")
                        {% if (!i) { %}
                            <button class="btn btn-warning cancel">
                                <i class="glyphicon glyphicon-ban-circle"></i>
                                <span>Cancel</span>
                            </button>
                        {% } %}
                        @endif
                    </td>
                </tr>
            {% } %}
            </script>
            <!-- The template to display files available for download -->
            <script id="{{uniqid()}}" class='download-template' type="text/x-tmpl">
            {% for (var i=0, file; file=o.files[i]; i++) { %}
                {% if (file.thumbnailUrl) { %}
                <tr data-item_id="{%=i%}" class="template-download fade">
                    <td class="vertical-middle preview-wrap">
                        <span class="preview">
                            {% if (file.thumbnailUrl) { %}
                                <input value="{%=file.url%}" class="upload-value" type="hidden" name="setting[{{$setting[0]->name}}][{{get_class($field)}}][{%=file.id%}]"/>
                                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.url%}" data-gallery><img src="{%=file.thumbnailUrl%}" alt="preview" class="img-thumbnail"></a>
                            {% } %}
                        </span>
                    </td>
                    <td class='vertical-middle'>
                        <p class="name">
                            {% if (file.url) { %}
                                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.url%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                            {% } else { %}
                                <span>{%=file.name%}</span>
                            {% } %}
                        </p>
                        {% if (file.error) { %}
                            <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                        {% } %}
                    </td>
                    <td  class='vertical-middle'>
                        <span class="size">{%=o.formatFileSize(file.size)%}</span>
                    </td>
                    <td class='vertical-middle'>
                        {% if (file.deleteUrl) { %}
                            <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="" {% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
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
            {% } %}
        </script>
        <?php
        ?>
        <script type="text/javascript">

            $(function() {

                var $container{{$niceName}} = $('div.upload.{{$niceName}}');
                var $form{{$niceName}} = $container{{$niceName}}.closest('div.wrap');
                // Initialize the jQuery File Upload widget:
                $form{{$niceName}}.fileupload({
                    // Uncomment the following to send cross-domain cookies:
                    //xhrFields: {withCredentials: true},
                    url: "{{{action('\Bootleg\Cms\ContentsController@postUpload', array('id'=>$setting[0]->id, 'type'=>get_class($setting[0])))}}}",
                    maxNumberOfFiles:{{$params->max_number or '1'}},
                    @if(isset($params->max_number) && $params->max_number>1)
                    singleFileUploads:'false',
                    @else
                    singleFileUploads:'true',
                    @endif
                    limitConcurrentUploads:3,
                    formData:{
                        type: '{{get_class($setting[0])}}',
                        '_token': "{{csrf_token()}}"
                    },
                    autoUpload: true,
                    disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
                    maxFileSize: {{$params->validation->size * 1024}},
                    acceptFileTypes: /(\.|\/)({{str_replace(',','|',$params->validation->mimes)}})$/i,

                    fileInput: $('input[type=file]', $container{{$niceName}}),

                    filesContainer: $('.files',$container{{$niceName}}),

                    uploadTemplateId: $('.upload-template', $container{{$niceName}}).attr('id'),

                    downloadTemplateId: $('.download-template', $container{{$niceName}}).attr('id'),

                });

                $form{{$niceName}}.bind('fileuploaddone', function (e, data) {
                    //added file, we wait for 1 second for some reason
                    setTimeout(function(){
                        //and add in the image preview
                        $input = $('input.upload-value', $container{{$niceName}});
                        $input.val($('span.preview img', $container{{$niceName}}).attr('src'));
                        window.parent.inline_image = $input.val();
                    }, 1000);
                }).bind('fileuploaddestroyed', function (e, data) {
                    //on deleted, we remove the input file
                    var $input = $('input.upload-value', $(data.context).closest('tr')).val('');
                    $input.clone().appendTo( $container{{$niceName}});

                    //$inp = $('input.file-url', $container{{$niceName}}).eq($(data.context).data('item_id'));
                    //$inp.attr('name',$inp.attr('name')+'[deleted]');
                    //$('input.file-url', $container{{$niceName}}).eq($(data.context).data('item_id')).remove();
                });

                @if(@$files)
                    var files{{$niceName}} = {!!$files!!};
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