<?php
$params = Contentsetting::parseParams($setting[0]);
$niceName = preg_replace('/\s+/', '', $setting[0]->name);
?>
{{ Form::label("setting[".$setting[0]->name."][".$setting[0]->id."]", ucfirst($setting[0]->name.":")) }}

<div class='text {{$niceName}}' >   
    @foreach($setting as $field)
    {{ Form::text("setting[".$field->name."][".get_class($field)."][".$field->id."]", $field->value, array('class'=>'form-control')) }}
    @endforeach
</div>
