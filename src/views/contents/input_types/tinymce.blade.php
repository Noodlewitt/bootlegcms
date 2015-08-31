<?php
if(@$content){

    $settingAfterEvent = \Event::fire('content.tinymce.draw', array('content'=>$content, 'setting'=>$setting));    
    $settingAfterEvent = reset($settingAfterEvent);
    if(!empty($settingAfterEvent)){
        $setting = $settingAfterEvent;
    }
}
$params = Contentsetting::parseParams($setting[0]);
$niceName = preg_replace('/\s+/', '', $setting[0]->name);
?>

{!! Form::label("setting[".$setting[0]->orig_name."][".$setting[0]->id."]", ucfirst($setting[0]->name.":")) !!}
<div id="upload_target" src="/random"></div>
@foreach($setting as $field)
    {!! Form::textarea("setting[".$field->orig_name."][".get_class($field)."][".$field->id."]", $field->value, array('class'=>'tinymce '.$niceName.$field->id, 'id'=>$niceName.$field->id)) !!}
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
                selector:'#{{$niceName.$field->id}}',
                plugins: ["link", "code", "hr", "image", "table", "media", "uploadImage"],
                toolbar:"undo redo | styleselect | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image upload",
                relative_urls: false,
                entity_encoding : "raw"
            });
        });
    </script>
@endforeach