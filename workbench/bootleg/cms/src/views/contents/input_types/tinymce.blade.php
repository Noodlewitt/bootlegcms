{{ Form::label("setting[".$setting[0]->name."][".$setting[0]->id."]", ucfirst($setting[0]->name.":")) }}
@foreach($setting as $field)
    {{ Form::textarea("setting[".$field->name."][".get_class($field)."][".$field->id."]", $field->value, array('class'=>'tinymce')) }}
@endforeach


<script>
    $(function() {
        tinymce.baseURL = '/cms_assets/vendor/tinymce-builded/js/tinymce';
        tinymce.init({
            selector:'textarea.tinymce',
            plugins: ["link", "code", "hr", "image", "table", "media"]
        });
    });
</script>