<?php

if(@$content){

    $settingAfterEvent = \Event::fire('content.tags.draw', array('content'=>$content, 'setting'=>$setting[0]));    
    $settingAfterEvent = reset($settingAfterEvent);
    if(!empty($settingAfterEvent)){
        $setting[0] = $settingAfterEvent;
    }
}

$params = Contentsetting::parseParams($setting[0]);

$niceName = preg_replace('/\s+/', '', $setting[0]->name);
$unique = uniqid();     
$values = (array)$params->values;

$options = array('class'=>'form-control','style'=>'width: 100%');
$options['multiple'] = 'multiple';
if(!@$params->simple){
    $options['class'] .= ' select2';
}

$tagsArr = explode(@$params->delimiter?$params->delimiter:',', $setting[0]->value);

//we flip this arrout so we can have the defaults included into this but not selected.
$tagsArr = array_flip($tagsArr);

foreach($tagsArr as $key=>$tagArr){
    $out[$key] = true;
}

$tagsArr = $out;

if(isset($tagsArr[''])){
    unset($tagsArr['']);
}

//we need to merge in any default values.
foreach($params->values as $key=>$default){
    if(!isset($tagsArr[$key])){
        $tagsArr[$key] = $default;    
    }
}

?>
<div class='form-group'>
    {!! Form::label("setting[".$setting[0]->name."][".$setting[0]->id."]", ucfirst($setting[0]->name.":")) !!}

    <div class='text {{$niceName}} {{$unique}}' >   
        <select id="{{$niceName}}{{$unique}}" name="setting[{{$setting[0]->name}}][{{get_class($setting[0])}}][{{$setting[0]->id}}][]" class="{{$options['class']}}" multiple="" tabindex="-1" aria-hidden="true">
            @foreach($tagsArr as $key=>$tag)
                <option {{$tag?'selected="selected"':''}}>{{$key}}</option>
            @endforeach
        </select>
    </div>
</div>
<script type="text/javascript">
$(function () {
    $('.{{$unique}} select').select2({
        @if(!@$params->fixed)
        tags:true,
        @endif
    });
});
</script>