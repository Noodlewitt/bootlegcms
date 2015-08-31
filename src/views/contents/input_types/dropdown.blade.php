<?php
$setting = \Event::fire('content.dropdown.draw', array('content'=>$content, 'setting'=>$setting));
$setting = reset($setting);

$params = Contentsetting::parseParams($setting[0]);
$niceName = preg_replace('/\s+/', '', $setting[0]->name);
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
foreach($setting as $key=>$field){
    $selectedTagsArr = explode(@$params->delimiter?$params->delimiter:',', $field->value);
}
$selectedTagsArr = array_flip($selectedTagsArr);    //and we want to flip this so it's easer to work with later.
?>
{!! Form::label("setting[".$setting[0]->orig_name."][".$setting[0]->id."][]", ucfirst($setting[0]->name.":")) !!}
@if($params->max_number  && $params->max_number > 1)
    <div class='text-fields'>
        @foreach($setting as $field)
            <select name="setting[{{$field->name}}][{{get_class($field)}}][{{$field->id}}][]" class="{{$options['class']}}" {{@$params->multiple?'multiple="multiple"':""}}  >
                @foreach($values as $key=>$value)
                    @if(isset($selectedTagsArr[$key]))
                    <option selected value="{{$key=="_empty_"?"":$key}}" >{{$value}}</option>
                    @else
                    <option value="{{$key=="_empty_"?"":$key}}" >{{$value}}</option>
                    @endif
                @endforeach
            </select>
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
        <select name="setting[{{$field->name}}][{{get_class($field)}}][{{$field->id}}][]" style="width:100%" class="{{$options['class']}}" {{@$params->multiple?'multiple="multiple"':""}}  >
            @foreach($values as $key=>$value)
                @if(isset($selectedTagsArr[$key]))
                <option selected value="{{$key=="_empty_"?"":$key}}" >{{$value}}</option>
                @else
                <option value="{{$key=="_empty_"?"":$key}}" >{{$value}}</option>
                @endif
            @endforeach
        </select>
    @endforeach
</div>
@endif


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