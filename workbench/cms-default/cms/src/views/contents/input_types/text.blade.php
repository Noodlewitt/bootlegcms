{{ Form::label('setting['.$setting->name.']', ucfirst($setting->name).':') }}
{{ Form::text('setting['.$setting->name.']', $setting->value, array('class'=>'form-control')) }}