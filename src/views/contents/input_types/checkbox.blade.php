<?php
/*
We have to do checkboxes as hidden fields becuase if it's unchecked http doesn't send the data over in POST.
In order to combat this rediculous issue I either have to re-m,odel the content-settings, template-settings behavior OR just do it with JS.
 */
if(@$content){

    $settingAfterEvent = \Event::fire('content.checkbox.draw', array('content'=>$content, 'setting'=>$setting));    
    $settingAfterEvent = reset($settingAfterEvent);
    if(!empty($settingAfterEvent)){
        $setting = $settingAfterEvent;
    }
}

$niceName = preg_replace('/\s+/', '', $setting[0]->name);
$params = Contentsetting::parseParams($setting[0]);

?>
@foreach($setting as $field)
    <div class='checkbox checkbox-block js-{{$niceName}}'>
        <label>{!! Form::checkbox("checkbox-$niceName", $field->value, $field->value, array('class'=>'js-checkbox')) !!} {{ucfirst($setting[0]->name)}}</label>
        {!! Form::hidden("setting[".$field->orig_name."][".get_class($field)."][".$field->id."]", $field->value, array('class'=>'js-hidden')) !!}
    </div>
@endforeach
<script>
    $('input.js-checkbox', $('.checkbox-block.js-{{$niceName}}')).change(function(e){
        e.preventDefault();
        $('input.js-hidden', $('.checkbox-block.js-{{$niceName}}')).val($(this).is(':checked') ? '{{$params->values->checked}}' : '{{$params->values->unchecked}}');
    });
</script>