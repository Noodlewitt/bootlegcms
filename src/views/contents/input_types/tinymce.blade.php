<?php
$params = Contentsetting::parseParams($setting[0]);
$niceName = preg_replace('/\s+/', '', $setting[0]->name);
$field_title = isset($params->field_title) ? $params->field_title : $setting[0]->name;
?>

{!! Form::label("setting[".$setting[0]->name."][".$setting[0]->id."]", ucfirst($field_title.":")) !!}
<div id="upload_target" src="/random"></div>
@foreach($setting as $field)
    {!! Form::textarea("setting[".$field->name."][".get_class($field)."][".$field->id."]", $field->value, array('class'=>'tinymce '.$niceName.$field->id, 'id'=>$niceName.$field->id)) !!}
    <script>
        var inline_image = "";
        $(function() {
             //purge any existing instances of this.
            tinymce.remove("#{{$niceName.$field->id}}");

            tinymce.PluginManager.add('uploadImage', function(editor, url) {
                // Add a button that opens a window
                editor.addButton('upload', {
                    text: 'Upload Image',
                    icon: 'image',
                    onclick: function() {
                        // Open window
                        editor.windowManager.open({
                            title: 'Upload Image',
                            url: '/cms/content/inline-upload',
                        //    body: [
                        //        {type: 'textbox', name: 'title', label: 'Title'}
                        //    ],
                            buttons: [{
                                text: 'Close',
                                onclick: 'close'
                            },
                            {
                                text:'OK',
                                onclick: function(){
                                    editor.execCommand('mceInsertContent', false, '<img src="' + inline_image +'" />');
                                    top.tinymce.activeEditor.windowManager.close();
                                }
                            }],
                        });
                    }
                });
            });
            tinymce.baseURL = '/vendor/bootleg/cms/components/tinymce-builded/js/tinymce';
            tinymce.init({
               height:{{$params->height}},
               relative_urls: false,
               selector:'#{{$niceName.$field->id}}',
               plugins: {!! json_encode($params->plugins)!!},
               toolbar:"{{$params->toolbar}}",
               content_css:"{{$params->content_css}}",
               @foreach((array) $params->tiny_parameters as $key=>$p)
                   {{$key}}:"{{$p}}",
               @endforeach
               entity_encoding : "raw"
           });
        });
    </script>
@endforeach
