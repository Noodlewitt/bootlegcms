<?php
$params = Contentsetting::parseParams($setting[0]);
$field_title = isset($params->field_title) ? $params->field_title : $setting[0]->name;
?>
{!! Form::label("setting[".$setting[0]->name."][".$setting[0]->id."]", ucfirst($field_title.":")) !!}
@foreach($setting as $field)
<h2>TODO: FINISH THIS </h2>
<div class='input-group'>
    <span class="input-group-addon"><i class='fa fa-facebook'></i></span>
    {!! Form::text("setting[".$field->name."][".$field->id."]", $field->value, array('class'=>'form-control')) !!}
</div>
@endforeach