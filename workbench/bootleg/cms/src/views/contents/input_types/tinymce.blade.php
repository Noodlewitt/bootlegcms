<?php
$params = Contentsetting::parseParams($setting[0]);
$niceName = preg_replace('/\s+/', '', $setting[0]->name);
?>

{{ Form::label("setting[".$setting[0]->name."][".$setting[0]->id."]", ucfirst($setting[0]->name.":")) }}
@foreach($setting as $field)
    {{ Form::textarea("setting[".$field->name."][".get_class($field)."][".$field->id."]", $field->value, array('class'=>'tinymce '.$niceName.$field->id, 'id'=>$niceName.$field->id)) }}
    <script>
        $(function() {
            //tinymce.remove();
            editor = tinymce.get("{{$niceName.$field->id}}");
            if(editor !== null){
                editor.remove();
            }
            tinymce.baseURL = '/cms_assets/vendor/tinymce-builded/js/tinymce';
            tinymce.init({
                selector:'textarea.{{$niceName.$field->id}}',
                plugins: ["link", "code", "hr", "image", "table", "media"]
            });
        });
    </script>
@endforeach

