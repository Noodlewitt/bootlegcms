<?php

if(@$content){

    $settingAfterEvent = \Event::fire('content.tags.draw', array('content'=>$content, 'setting'=>$setting));    
    $settingAfterEvent = reset($settingAfterEvent);
    if(!empty($settingAfterEvent)){
        $setting = $settingAfterEvent;
    }
}
$params = Contentsetting::parseParams($setting);
$niceName = preg_replace('/\s+/', '', $setting->name);
$unique = uniqid();
$values = (array)$params->values;

$options = array('class'=>'form-control','style'=>'width: 100%');
$options['multiple'] = 'multiple';
if(!@$params->simple){
    $options['class'] .= ' select2';
}
$tagsArr = explode(@$params->delimiter?$params->delimiter:',', $setting->value)

//dd($tagsArr);
?>
<div class='form-group'>
    {!! Form::label("setting[".$setting->orig_name."][".$setting->id."]", ucfirst($setting->name.":")) !!}

    <div class='text {{$niceName}} {{$unique}}' >   
        <select id="{{$niceName}}{{$unique}}" name="setting[{{$setting->name}}][{{get_class($setting)}}][{{$setting->id}}][]" class="{{$options['class']}}" multiple="" tabindex="-1" aria-hidden="true">
            @foreach($tagsArr as $tag)
                @if($tag)
                    <option selected="selected">{{$tag}}</option>
                @endif
            @endforeach
            @foreach($values as $standardValue)
                <option>{{$standardValue}}</option>
            @endforeach
        </select>
    </div>
</div>
<script type="text/javascript">
$(function () {
    $('.{{$unique}} select').select2({
        tags:true
    });
});
</script>