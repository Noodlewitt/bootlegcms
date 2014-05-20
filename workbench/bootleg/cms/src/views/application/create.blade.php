<div class="col-md-offset-2 col-md-10">
    <div class="page-header row">	
            <!-- Page header, center on small screens -->
            <h1 class="col-xs-12"><i class="glyphicon glyphicon-cog"></i>&nbsp;&nbsp;Create Application</h1>
    </div>
    
    
    <div class="row">
        <div class="col-xs-12">
            {{ Form::open(array('action' => 'ApplicationController@postStore', 'class'=>'main-form')) }}
            <ul>
                <li class="form-group">
                    {{ Form::label('name', 'Name:') }}
                    {{ Form::text('name', null, array('class'=>'form-control')) }}
                </li>
                <li class="form-group">
                    {{ Form::label('name', 'Name:') }}
                    {{ Form::select('theme', $themes , Input::old('theme'), array('class'=>'form-control')) }}
                </li>
                <li class="form-group">
                    <div class='btn-group btn-group-lg'>
                        {{ Form::submit('Create', array('class' => 'btn btn-success')) }}
                        {{ link_to_action('UsersController@anyDashboard', 'Cancel', null, array('class' => 'btn btn-danger')) }}
                    </div>
                </li>
            </ul>
            {{ Form::close() }}
        </div>
    </div>
</div>