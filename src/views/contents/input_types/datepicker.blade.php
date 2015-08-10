<?php
$params = Contentsetting::parseParams($setting[0]);
$niceName = preg_replace('/\s+/', '', $setting[0]->name);
$options = array('data-provide'=>"datepicker", 'class'=>'form-control datepicker');
$options2 = ((array) $params->options);
$options = array_merge($options, $options2);
?>
{!! Form::label("setting[".$setting[0]->orig_name."][".$setting[0]->id."]", ucfirst($setting[0]->name.":")) !!}
@if($params->max_number  && $params->max_number > 1)
    <div class='text-fields'>
        @foreach($setting as $field)
        <div class='input-group datetimepicker {{$niceName}}' >   
            {!! Form::text("setting[".$field->orig_name."][".get_class($field)."][".$field->id."]", $field->value, $options) !!}
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
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
<div class='datetime input-group {{$niceName}}' >   
    @foreach($setting as $field)
    {!! Form::text("setting[".$field->orig_name."][".get_class($field)."][".$field->id."]", $field->value, $options)!!}
    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
    @endforeach
</div>
@endif

<script type="text/javascript">
$(function () {
    $('.{{$niceName}}').datetimepicker({
        'format':'YYYY-MM-DD H:mm:SS',
        'useCurrent':true
    });
});
</script>

