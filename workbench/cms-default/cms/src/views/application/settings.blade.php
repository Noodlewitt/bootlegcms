@extends('cms::layouts.scaffold')

@section('main')
<div class="col-md-10 fullheight">
    <h1>Applicaiton Settings</h1>
    {{ Form::model($application, array('method' => 'PATCH', 'action' => array('ApplicationController@anyUpdate'))) }}
    <ul>
        <li class="form-group">
            {{ Form::label('name', 'Name:') }}
            {{ Form::text('name', null, array('class'=>'form-control')) }}
        </li>
        <li class="form-group">
            {{ Form::label('theme', 'Theme:') }}
            {{ Form::select('theme', Theme::lists('name', 'id'), null, array('class'=>'form-control')) }}
        </li>
        
        @foreach($application_settings as $setting)
            <li class="form-group">
                @include('cms::contents.input_types.'.$setting->field_type, array('setting'=>$setting, 'content_settings'=>$application_settings))
            </li>
        @endforeach
    </ul>
    {{ Form::close() }}
</div>
@stop
