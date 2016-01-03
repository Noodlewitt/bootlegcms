<?php
if(@$content){

    $settingAfterEvent = \Event::fire('content.colour.draw', array('content'=>$content, 'setting'=>$setting));    
    $settingAfterEvent = reset($settingAfterEvent);
    if(!empty($settingAfterEvent)){
        $setting = $settingAfterEvent;
    }
}
$params = Contentsetting::parseParams($setting);
$niceName = preg_replace('/\s+/', '', $setting->name);
?>
<div class='form-group'>
    {!! Form::label("setting[".$setting->name."][".$setting->id."]", ucfirst($setting->name.":")) !!}
    <div class='text {{$niceName}}' >  
        {!! Form::text("setting[".$setting->name."][".get_class($setting)."][".$setting->id."]", $setting->value, array('class'=>'form-control')) !!}
    </div>
</div>
<script>
    $(function(){
        $('.{{$niceName}} input').colorpicker();
    });
</script>

