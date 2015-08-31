<?php
if(@$content){
    $settingAfterEvent = \Event::fire('content.upload.draw', array('content'=>$content, 'setting'=>$setting));    
    $settingAfterEvent = reset($settingAfterEvent);
    if(!empty($settingAfterEvent)){
        $setting = $settingAfterEvent;
    }
}


$params = Contentsetting::parseParams($setting[0]);
$niceName = preg_replace('/\s+/', '_', $setting[0]->orig_name);
$files = array();
foreach($setting as $field){
    if(@$field->orig_name){
        
        $url = $field->value;

        $fileName = pathinfo($url,PATHINFO_FILENAME);
        $fileObj = new stdClass();
        $fileObj->orig_name = $fileName;
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
$unique = uniqid();
?>
<div class="wrap">
    <div class='upload {{$niceName}}-{{$unique}}' >   
        @if($setting[0]->orig_name !="_inline")
        {!! Form::label("setting[".$setting[0]->orig_name."][".$setting[0]->id."]", ucfirst($setting[0]->orig_name.":")) !!}
        @endif
        

            <!-- The table listing the files available for upload/download -->
            <table id="{{uniqid()}}" role="presentation" class="table table-striped uploaded"><tbody class="files"></tbody></table>

            <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
            <div class="row fileupload-buttonbar">
                <div class="col-sm-12
                @if($setting[0]->orig_name == "_inline")
                    text-center
                @endif
                ">
                    <div class="btn-group">
                    <!-- The fileinput-button span is used to style the file input field as button -->
                        
                        @if($params->max_number == 1)
                            <span class="btn btn-success fileinput-button">
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Choose file...</span>
                                <input type="file" name="{{$setting[0]->orig_name}}[]" multiple>
                            </span>
                        @else
                            <span class="btn btn-success fileinput-button">
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Add file...</span>
                                <input type="file" name="{{$setting[0]->orig_name}}[]" multiple>
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
                    @if($setting[0]->orig_name !="_inline")
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
                        @if($setting[0]->orig_name !="_inline")
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
                                <input value="{%=file.url%}" class="upload-value" type="hidden" name="setting[{{$setting[0]->orig_name}}][{{get_class($field)}}][{%=file.id%}]"/>
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

        <script type="text/javascript">

            $(function() {

                var $container = $('div.upload.{{$niceName}}-{{$unique}}');
                var $form = $container.closest('div.wrap');
                // Initialize the jQuery File Upload widget:
                $form.fileupload({
                    // Uncomment the following to send cross-domain cookies:
                    //xhrFields: {withCredentials: true},
                    @if(@$applicationItem)
                        url: "{{{action('\Bootleg\Cms\ContentsController@postUpload', array('id'=>$setting[0]->id, 'type'=>'Applicationsetting'))}}}",
                    @elseif(@$contentItem)
                        url: "{{{action('\Bootleg\Cms\ContentsController@postUpload', array('id'=>$setting[0]->id, 'type'=>'Contentsetting'))}}}",
                    @elseif(@$templateItem)
                        url: "{{{action('\Bootleg\Cms\ContentsController@postUpload', array('id'=>$setting[0]->id, 'type'=>'Templatesetting'))}}}",
                    @else(@$contentItem)
                        url: "{{{action('\Bootleg\Cms\ContentsController@postUpload')}}}",
                    @endif
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

                    fileInput: $('input[type=file]', $container),

                    filesContainer: $('.files',$container),

                    uploadTemplateId: $('.upload-template', $container).attr('id'),

                    downloadTemplateId: $('.download-template', $container).attr('id'),

                });

                $form.bind('fileuploaddone', function (e, data) {
                    //added file, we wait for 1 second for some reason                  
                    setTimeout(function(){
                        //and add in the image preview
                        $input = $('input.upload-value', $container);
                        $input.val($('span.preview img', $container).attr('src'));
                        window.parent.inline_image = $input.val();
                    }, 1000);
                }).bind('fileuploaddestroyed', function (e, data) {     
                    //on deleted, we remove the input file
                    var $input = $('input.upload-value', $(data.context).closest('tr')).val('');
                    $input.clone().appendTo( $container);
                    
                    //$inp = $('input.file-url', $container{{$niceName}}).eq($(data.context).data('item_id'));
                    //$inp.attr('name',$inp.attr('name')+'[deleted]');
                    //$('input.file-url', $container{{$niceName}}).eq($(data.context).data('item_id')).remove();
                }); 

                @if(@$files)
                    var files = {!!$files!!};
                    $form.fileupload('option', 'done').call($form, $.Event('done'), {result: {files: files }});
                @endif

                // Enable iframe cross-domain access via redirect option:
                $form.fileupload('option', 'redirect',
                window.location.href.replace(/\/[^\/]*$/, '/cors/cors.html?%s'));
            });

        </script>
    </div>
</div>