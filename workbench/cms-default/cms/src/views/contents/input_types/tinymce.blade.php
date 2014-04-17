{{ Form::label($setting->name, ucfirst($setting->name).':') }}
{{ Form::textarea($setting->name, $setting->value, array('class'=>'tinymce')) }}

<script>
    
    //$('textarea.tinymce').tinymce();
    tinymce.baseURL = '/cms_assets/vendor/tinymce-builded/js/tinymce'
    tinymce.init({
        selector:'textarea.tinymce',
        plugins: ["link", "code", "hr", "image", "table", "media"]
    });
</script>