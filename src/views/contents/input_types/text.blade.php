<?php
if(@$content){

    $settingAfterEvent = \Event::fire('content.text.draw', array('content'=>$content, 'setting'=>$setting));
    $settingAfterEvent = reset($settingAfterEvent);
    if(!empty($settingAfterEvent)){
        $setting = $settingAfterEvent;
    }
}
$params = \Contentsetting::parseParams($setting);
$niceName = preg_replace('/\s+/', '', $setting->name);
$options = array('class'=>'form-control');
if(@$params->tooltip->text){
    $options['data-toggle'] = "tooltip";
    $options['data-placement'] = @$params->tooltip->postion?$params->tooltip->postion:"left";
    $options['title'] = $params->tooltip->text;
}
?>
<div class='form-group'>
    {!! Form::label("setting[".$setting->name."][".$setting->id."]", ucfirst($setting->name.":")) !!}
    <div class='text {{$niceName}}' >
        {!! Form::text("setting[".$setting->name."][".get_class($setting)."][".$setting->id."]", $setting->value, $options) !!}
    </div>
</div>

