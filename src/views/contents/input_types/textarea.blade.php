<?php
if(@$content){

    $settingAfterEvent = \Event::fire('content.textarea.draw', array('content'=>$content, 'setting'=>$setting));    
    $settingAfterEvent = reset($settingAfterEvent);
    if(!empty($settingAfterEvent)){
        $setting = $settingAfterEvent;
    }
}
?>
<div class='form-group'>
    {!! Form::label("setting[".$setting->name."][".$setting->id."]", ucfirst($setting->name.":")) !!}
    {!! Form::textarea("setting[".$setting->name."][".get_class($setting)."][".$setting->id."]", $setting->value, array('class'=>'form-control')) !!}
</div>