{{ Form::label('setting['.$setting->name.']', ucfirst($setting->name).':') }}
{{ Form::textarea('setting['.$setting->name.']', $setting->value, array('class'=>'form-control')) }}