<?php
if(@$content){

    $settingAfterEvent = \Event::fire('content.datepicker.draw', array('content'=>$content, 'setting'=>$setting));    
    $settingAfterEvent = reset($settingAfterEvent);
    if(!empty($settingAfterEvent)){
        $setting = $settingAfterEvent;
    }
}
$params = Contentsetting::parseParams($setting);
$niceName = preg_replace('/\s+/', '', $setting->name);
$options = array('class'=>'form-control');
$options2 = ((array) $params->options);
$options = array_merge($options, $options2);
//dd($options);

if(@$name === false){
$name = NULL; //we don't want to set it at all!
}
else if(@$name){
//$name = $name;
}
else{
$name = "setting[".$setting->name."][".get_class($setting)."][".$setting->id."]";
}

?>
{!! Form::label($name, ucfirst($setting->name.":")) !!}
<div class='form-group'>
    <div class='datetime input-group date-{{$niceName}}' >   
        {!! Form::text($name, $setting->value, $options)!!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>

<script type="text/javascript">
$(function () {
    $('.date-{{$niceName}}').datetimepicker({
        'sideBySide':true
    });
});
</script>

