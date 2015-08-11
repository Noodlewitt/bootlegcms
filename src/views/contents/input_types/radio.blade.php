<?php

$params = Contentsetting::parseParams($setting[0]);
$niceName = isset($params->field_title) ? $params->field_title : preg_replace('/\s+/', '', $setting[0]->name);

?>
<div><label>{{ ucfirst($niceName) }}</label></div>
@foreach($setting as $field)
	@foreach($params->values as $key=>$value)
		<div class="radio{{ (isset($params->display) && $params->display == 'inline') ? '-inline' : '' }}">
		  <label>{!! Form::radio("setting[".$field->name."][".get_class($field)."][".$field->id."]", $value, $setting[0]->value == $value ? true : false) !!} {{ ucfirst($key) }}</label>
		</div>
	@endforeach
@endforeach