<?php
//TOOD: echo field out to page. edit disabled.
?>
{{ Form::label($setting->name, ucfirst($setting->name).':') }}
{{ $setting->value }}