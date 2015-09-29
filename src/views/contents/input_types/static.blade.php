<?php
if(@$content){

    $settingAfterEvent = \Event::fire('content.static.draw', array('content'=>$content, 'setting'=>$setting));    
    $settingAfterEvent = reset($settingAfterEvent);
    if(!empty($settingAfterEvent)){
        $setting = $settingAfterEvent;
    }
}
$field_name = @$setting->name;
?>
<div class='form-group'>
    {{ Form::label("setting[$field_name]", ucfirst("$field_name:")) }}
    <div class=''> {{ $setting->value }}</div>
</div>