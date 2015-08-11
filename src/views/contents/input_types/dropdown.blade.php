<?php
$params = Contentsetting::parseParams($setting[0]);
$niceName = preg_replace('/\s+/', '', $setting[0]->name);
$field_title = isset($params->field_title) ? $params->field_title : $setting[0]->name;
$values = (array)$params->values;
?>
{!! Form::label("setting[".$setting[0]->name."][".$setting[0]->id."]", ucfirst($field_title.":")) !!}
@if($params->max_number  && $params->max_number > 1)
    <div class='text-fields'>
        @foreach($setting as $field)
        <div class='input-group text {{$niceName}}' >
            {!! Form::select("setting[".$field->name."][".get_class($field)."][".$field->id."]", $values, $field->value, array('class'=>'form-control')) !!}
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
            $('.text.{{$niceName}}').parent().append('<div class="input-group text"><input class="form-control" name="setting[{{$setting[0]->name}}][Contentsetting][]" type="text"><span class="input-group-btn"><button class="btn btn-danger" type="button"><span class="glyphicon glyphicon-remove"></span></button></span></div>');
        });
        $('.del-row').click(function(e){
            e.preventDefault();
            var input_name = $('input',$(this).parent().parent()).attr('name');
            $(this).parent().parent().remove();
            $('.text-fields').append('<input class="hidden" type="hidden" name="'+input_name+'" value="deleted">');
        });
    </script>
@else
<div class='text {{$niceName}}' >
    @foreach($setting as $field)
    {!! Form::select("setting[".$field->name."][".get_class($field)."][".$field->id."]",  $values, $field->value, array('class'=>'form-control'))!!}
    @endforeach
</div>
@endif