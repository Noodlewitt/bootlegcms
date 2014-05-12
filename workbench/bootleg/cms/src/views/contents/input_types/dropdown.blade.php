<?php
$field_name = @$setting->name;
$field_value = (@$setting->value?$setting->value:$setting->value);

//we need to decode the dropdown parameters.. TODO: JASON OBJECT FOR THIS.
$fields = explode('|',$setting->field_parameters);
$final = array();
foreach($fields as $key=>$field){
    $f = explode(':', $field);
    $final[$f[1]] = $f[0];
}
?>
{{ Form::select($setting->name, $final, $field_value) }}