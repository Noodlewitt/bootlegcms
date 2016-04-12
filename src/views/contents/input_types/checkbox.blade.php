<?php
/*
We have to do checkboxes as hidden fields becuase if it's unchecked http doesn't send the data over in POST.
In order to combat this rediculous issue I either have to re-m,odel the content-settings, template-settings behavior OR just do it with JS.
 */
if(@$content){

    $settingAfterEvent = \Event::fire('content.checkbox.draw', array('content'=>$content, 'setting'=>$setting));    
    $settingAfterEvent = reset($settingAfterEvent);
    if(!empty($settingAfterEvent)){
        $setting = $settingAfterEvent;
    }
}

if(@$name === false){
$name = NULL; //we don't want to set it at all!
}
else if(@$name){
//$name = $name;
}
else{
$name = "setting[".$setting->name."][".get_class($setting)."][".$setting->id."]";
}

$niceName = preg_replace('/\s+/', '', $setting->name);
$params = Contentsetting::parseParams($setting);

?>
<div class='checkbox checkbox-block js-{{$niceName}}'>
    <label>{!! Form::checkbox("checkbox-$niceName", $setting->value, $setting->value, array('class'=>'js-checkbox')) !!} {{ucfirst($setting->name)}}</label>
    {!! Form::hidden($name, $setting->value, array('class'=>'js-hidden')) !!}
</div>
<script>
    $('input.js-checkbox', $('.checkbox-block.js-{{$niceName}}')).change(function(e){
        e.preventDefault();
        $('input.js-hidden', $('.checkbox-block.js-{{$niceName}}')).val($(this).is(':checked') ? '{{$params->values->checked}}' : '{{$params->values->unchecked}}');
    });
</script>