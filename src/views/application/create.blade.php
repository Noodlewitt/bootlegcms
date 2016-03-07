@extends(view()->exists($cms_package.'::layouts.master') ? $cms_package.'::layouts.master' : 'cms::layouts.master')
@section('main-content')
    <div class="col-md-offset-2 col-md-10">
        <div class="page-header row">	
                <!-- Page header, center on small screens -->
                <h1 class="col-xs-12"><i class="glyphicon glyphicon-cog"></i>Create Application</h1>
        </div>
        
        
        <div class="row">
            <div class="col-xs-12">
                {!! Form::model($newApp, array('action' => '\Bootleg\Cms\ApplicationController@postStore', 'class'=>'main-form')) !!}
                <ul>
                    <li class="form-group {{@$errors->first('name') ? 'has-error has-feedback':""}}">
                        {!! Form::label('name', 'Name:', ['class'=>'control-label']) !!}
                        {!! Form::text('name', \Input::old('name'), array('class'=>'form-control')) !!}
                        @if(@$errors->first('name'))
                            <span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span>
                            {{@$errors->first('name')}}
                        @endif
                    </li>
                    <li class="form-group {{@$errors->first('domain') ? 'has-error has-feedback':""}}">
                        {!! Form::label('domain', 'Domain(s):', ['class'=>'control-label']) !!}
                        {!! Form::text('domain', \Input::old('domain'), array('class'=>'form-control tag')) !!}
                        @if(@$errors->first('domain'))
                            <span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span>
                            {{@$errors->first('domain')}}
                        @endif
                    </li>

                    <li class="form-group {{@$errors->first('parent_id') ? 'has-error has-feedback':""}}">
                        {!! Form::label('parent_id', 'Parent Store:', ['class'=>'control-label']) !!}
                        {!! Form::text('parent_id', \Input::old('parent_id'), array('class'=>'form-control')) !!}
                        @if(@$errors->first('parent_id'))
                            <span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span>
                            {{@$errors->first('parent_id')}}
                        @endif
                    </li>
                    <li class="form-group">
                        <div class='btn-group btn-group-lg'>
                            {!! Form::submit('Create', array('class' => 'btn btn-success')) !!}
                            {!! link_to_action('\Bootleg\Cms\UsersController@anyDashboard', 'Cancel', null, array('class' => 'btn btn-danger')) !!}
                        </div>
                    </li>
                </ul>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <script>
    $(function() {
        $('input.tag').tagsinput('items');
    });
    </script>
@stop
