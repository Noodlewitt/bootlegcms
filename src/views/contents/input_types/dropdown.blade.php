<?php

if(@$content){

    $settingAfterEvent = \Event::fire('content.dropdown.draw', array('content'=>$content, 'setting'=>$setting));    
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
if(@$params->multiple){
    $options['multiple'] = 'multiple';
}
if(!@$params->simple){
    $options['class'] .= ' select2';
}
if(!@$params->placeholder){
    $params->placeholder = @$values['_empty_']?$values['_empty_']:reset($values);
}

//if this is a multiple select it's possible we might have miltiple in here.. so split it on the delimeter and work with that.
$selectedTagsArr = explode(@$params->delimiter?$params->delimiter:',', $setting->value);
$selectedTagsArr = array_flip($selectedTagsArr);    //and we want to flip this so it's easer to work with later.
?>
<div class='form-group'>
    {!! Form::label("setting[{{$setting->name}}][{{get_class($setting)}}][{{$setting->id}}]", ucfirst($setting->name.":")) !!}

    <div class='text {{$niceName}} {{$unique}}' >   
        <select name="setting[{{$setting->name}}][{{get_class($setting)}}][{{$setting->id}}]" style="width:100%" class="{{$options['class']}}" {{@$params->multiple?'multiple="multiple"':""}}  >
            @foreach($values as $key=>$value)
                @if(isset($selectedTagsArr[$key]))
                <option selected value="{{$key=="_empty_"?"":$key}}" >{{$value}}</option>
                @else
                <option value="{{$key=="_empty_"?"":$key}}" >{{$value}}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>

@if(@$params->simple)
<script type="text/javascript">
$(function () {
    $('.{{$unique}} select.select2').select2({
        @if(@$params->maxTags)
            maxSelectionLength:{{$params->maxTags}},
        @endif

        @if(@$params->search === false)
            minimumResultsForSearch: Infinity,
        @elseif(@$params->search && @$params->search !== true)
            minimumResultsForSearch: $params->search,
        @endif
        placeholder: "{{@$params->placeholder}}",
    });
});
</script>
@endif