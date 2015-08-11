<?php
$params = Contentsetting::parseParams($setting[0]);
$niceName = isset($params->field_title) ? $params->field_title : preg_replace('/\s+/', '', $setting[0]->name);
?>

{!! Form::label("setting[".$setting[0]->name."][".$setting[0]->id."]", ucfirst($niceName.":")) !!}
@foreach($setting as $field)
    {!! Form::textarea("setting[".$field->name."][".get_class($field)."][".$field->id."]", $field->value, array('class'=>'form-control')) !!}
@endforeach
