<?php
/*
We have to do checkboxes as hidden fields becuase if it's unchecked http doesn't send the data over in POST.
In order to combat this rediculous issue I either have to re-m,odel the content-settings, template-settings behavior OR just do it with JS.
 */
$params = Contentsetting::parseParams($setting[0]);
$niceName = preg_replace('/\s+/', '', $setting[0]->name);
$field_title = isset($params->field_title) ? $params->field_title : $setting[0]->name;

?>
@foreach($setting as $field)
    <div class='checkbox checkbox-block js-{{$niceName}}'>
        <label>{!! Form::checkbox("checkbox-$niceName", $field->value, $field->value, array('class'=>'js-checkbox')) !!} {{ucfirst($field_title)}}</label>
        {!! Form::hidden("setting[".$field->name."][".get_class($field)."][".$field->id."]", $field->value, array('class'=>'js-hidden')) !!}
    </div>
@endforeach
<script>
    $('input.js-checkbox', $('.checkbox-block.js-{{$niceName}}')).change(function(e){
        e.preventDefault();
        $('input.js-hidden', $('.checkbox-block.js-{{$niceName}}')).val($(this).is(':checked') ? '{{$params->values->checked}}' : '{{$params->values->unchecked}}');
    });
</script>