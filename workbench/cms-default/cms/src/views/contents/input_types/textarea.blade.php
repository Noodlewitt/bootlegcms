{{ Form::label($setting->name, ucfirst($setting->name).':') }}
{{ Form::textarea($setting->name, $setting->value) }}