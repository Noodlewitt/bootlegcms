<div class="col-md-offset-2 col-md-10">
    <div class="page-header row">	
            <!-- Page header, center on small screens -->
            <h1 class="col-xs-12"><i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;Users {{ link_to_action('UsersController@anyCreate', 'Create User', null, array('class'=>'btn btn-primary pull-right')) }}</h1>
    </div>
    <div class="row">
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
            <li class="form-group">
                {{ Form::submit('Update', array('class' => 'btn btn-success')) }}
                {{ link_to_action('ApplicationController@anySettings', 'Cancel', @$content->id, array('class' => 'btn')) }}
            </li>
        </ul>
        {{ Form::close() }}
    </div>
</div>
