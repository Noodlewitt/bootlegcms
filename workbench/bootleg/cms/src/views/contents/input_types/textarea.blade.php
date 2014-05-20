{{ Form::label("setting[".$setting[0]->name."][".$setting[0]->id."]", ucfirst($setting[0]->name.":")) }}
@foreach($setting as $field)
    {{ Form::textarea("setting[".$field->name."][".get_class($field)."][".$field->id."]", $field->value, array('class'=>'form-control')) }}
@endforeach
