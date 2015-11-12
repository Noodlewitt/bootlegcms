<?php
$params = Contentsetting::parseParams($setting[0]);
$niceName = preg_replace('/\s+/', '', $setting[0]->name);
$field_title = isset($params->field_title) ? $params->field_title : $setting[0]->name;

if($params->is_baum) {
    $options = \DB::table($params->table)->select($params->id_col,$params->value_col,'depth')->orderBy('lft', 'ASC')->get();
} else {
    $options = \DB::table($params->table)->select($params->id_col,$params->value_col)->get();
}

foreach($options as $option){
    $opt = (array) $option;
    $values[$opt[$params->id_col]]=($params->is_baum && $opt['depth'] > 0 ? str_repeat('--',$opt['depth']).' ' : '').$opt[$params->value_col];
}

?>
{!! Form::label("setting[".$setting[0]->name."][".$setting[0]->id."]", ucfirst($field_title.":")) !!}
<div class='text {{$setting[0]->name}}' >
    @foreach($setting as $field)
    {!! Form::select("setting[".$field->name."][".get_class($field)."][".$field->id."]", $values, $setting[0]->value, array('class'=>'form-control')) !!}
    @endforeach
</div>