<?php
if(@$content){

    $settingAfterEvent = \Event::fire('content.tinymce.draw', array('content'=>$content, 'setting'=>$setting));    
    $settingAfterEvent = reset($settingAfterEvent);
    if(!empty($settingAfterEvent)){
        $setting = $settingAfterEvent;
    }
}
$params = Contentsetting::parseParams($setting);
$niceName = preg_replace('/\s+/', '', $setting->name);
?>
<div class='form-group'>
    {!! Form::label("setting[".$setting->orig_name."][".$setting->id."]", ucfirst($setting->name.":")) !!}
    <div id="upload_target" src="/random"></div>
    {!! Form::textarea("setting[".$setting->orig_name."][".get_class($setting)."][".$setting->id."]", $setting->value, array('class'=>'tinymce '.$niceName.$setting->id, 'id'=>$niceName.$setting->id)) !!}
</div>
<script>
    var inline_image = "";
    $(function() {
         //purge any existing instances of this.
        tinymce.remove("#{{$niceName.$setting->id}}");

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
            selector:'#{{$niceName.$setting->id}}',
            plugins: ["link", "code", "hr", "image", "table", "media", "uploadImage"],
            toolbar:"undo redo | styleselect | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image upload",
            relative_urls: false,
            entity_encoding : "raw"
        });
    });
</script>