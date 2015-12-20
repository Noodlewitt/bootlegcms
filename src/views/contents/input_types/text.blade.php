<?php
if($content){

    $settingAfterEvent = \Event::fire('content.text.draw', array('content'=>$content, 'setting'=>$setting));
    $settingAfterEvent = reset($settingAfterEvent);
    if(!empty($settingAfterEvent)){
        $setting = $settingAfterEvent;
    }
}
$params = \Contentsetting::parseParams($setting);
$niceName = preg_replace('/\s+/', '', $setting->name);
$options = array('class'=>'form-control');
if($params->tooltip->text){
    $options['data-toggle'] = "tooltip";
    $options['data-placement'] = $params->tooltip->postion?$params->tooltip->postion:"left";
    $options['title'] = $params->tooltip->text;
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

if(isset($opts)){
    //we can pass in specific options too if we want to.
    foreach($opts as $key=>$opt){
        $options[$key] = $opt;
    }
}

?>
<div class='form-group'>
    {!! Form::label($name, ucfirst($setting->name.":")) !!}
    <div class='text {{$niceName}}' >
        {!! Form::text($name, $setting->value, $options) !!}
    </div>
</div>
