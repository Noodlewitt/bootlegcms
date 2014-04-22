@extends('cms::layouts.scaffold')

@section('main')
<div id="content-wrapper">
    <div class="page-header">	
        <div class="row">
            <!-- Page header, center on small screens -->
            <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-cogs page-header-icon"></i>&nbsp;&nbsp;Settings</h1>
            {{ link_to_action('UsersController@anyCreate', 'Create User', null, array('class'=>'btn btn-primary pull-right')) }}
        </div>
    </div>
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
