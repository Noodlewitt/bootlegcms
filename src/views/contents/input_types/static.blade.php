<?php
if(@$content){

    $settingAfterEvent = \Event::fire('content.static.draw', array('content'=>$content, 'setting'=>$setting));    
    $settingAfterEvent = reset($settingAfterEvent);
    if(!empty($settingAfterEvent)){
        $setting = $settingAfterEvent;
    }
}
$field_name = @$setting->name;
$field_value = (@$setting->value?$setting->value:$setting->value);
?>
{{ Form::label("setting[$field_name]", ucfirst("$field_name:")) }}
{{ $field_value }}