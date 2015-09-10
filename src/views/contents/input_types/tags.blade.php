<?php

if(@$content){

    $settingAfterEvent = \Event::fire('content.tags.draw', array('content'=>$content, 'setting'=>$setting));    
    $settingAfterEvent = reset($settingAfterEvent);
    if(!empty($settingAfterEvent)){
        $setting = $settingAfterEvent;
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
foreach($setting as $key=>$field){
    $tagsArr = explode(@$params->delimiter?$params->delimiter:',', $field->value);
}
//dd($tagsArr);
?>
{!! Form::label("setting[".$setting[0]->orig_name."][".$setting[0]->id."]", ucfirst($setting[0]->name.":")) !!}
@if($params->max_number  && $params->max_number > 1)
    <div class='text-fields'>
        @foreach($setting as $field)
        <div class='input-group text {{$niceName}} $unique' > 
            {{-- we have to do the select box manually since it doesn't allow for multiples--}}
            <select name="setting[{{$field->name}}][{{get_class($field)}}][{{$field->id}}][]" class="{{$options['class']}}" multiple="" tabindex="-1" aria-hidden="true">
                @foreach($tagsArr as $tag)
                    <option selected="selected">$tag</option>
                @endforeach
            </select>
            <span class="input-group-btn">
                <button class="del-row btn btn-danger" type="button"><span class='glyphicon glyphicon-remove'></span></button>
            </span>
        </div>
        @endforeach
    </div>
    <button class='add-row btn btn-primary btn-sm pull-right'>Add Row</button>

    <script>
        $('.add-row').click(function(e){
            e.preventDefault();
            $('.text.{{$niceName}}').parent().append('<div class="input-group text"><input class="form-control" name="setting[{{$setting[0]->orig_name}}][Contentsetting][]" type="text"><span class="input-group-btn"><button class="btn btn-danger" type="button"><span class="glyphicon glyphicon-remove"></span></button></span></div>');
        });
        $('.del-row').click(function(e){
            e.preventDefault();
            var input_name = $('input',$(this).parent().parent()).attr('name');
            $(this).parent().parent().remove();
            $('.text-fields').append('<input class="hidden" type="hidden" name="'+input_name+'" value="deleted">');
        });
    </script>
@else
<div class='text {{$niceName}} {{$unique}}' >   
    @foreach($setting as $field)
        <select id="{{$niceName}}{{$unique}}" name="setting[{{$field->name}}][{{get_class($field)}}][{{$field->id}}][]" class="{{$options['class']}}" multiple="" tabindex="-1" aria-hidden="true">
            @foreach($tagsArr as $tag)
                @if($tag)
                    <option selected="selected">{{$tag}}</option>
                @endif
            @endforeach
            @foreach($values as $standardValue)
                <option>{{$standardValue}}</option>
            @endforeach
        </select>
    @endforeach
</div>
@endif


<script type="text/javascript">
$(function () {
    $('.{{$unique}} select').select2({
        tags:true
    });
});
</script>