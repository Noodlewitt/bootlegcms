<?php
$field_name = @$setting->name;
$field_value = (@$setting->value?$setting->value:$setting->value);
?>
{{ Form::label("setting[$field_name]", ucfirst("$field_name:")) }}
{{ $field_value }}