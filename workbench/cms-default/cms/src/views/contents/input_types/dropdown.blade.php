{{ Form::label($setting->name, ucfirst($setting->name).':') }}
<?php
//we need to decode the dropdown parameters..
$fields = explode('|',$setting->field_parameters);
$final = array();
foreach($fields as $key=>$field){
    $f = explode(':', $field);
    $final[$f[1]] = $f[0];
}
?>
{{ Form::select($setting->name, $final, $setting->value) }}