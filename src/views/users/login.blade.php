@extends(view()->exists($cms_package.'::layouts.bare') ? $cms_package.'::layouts.bare' : 'cms::layouts.bare')
@section('main-content')
@include('cms::layouts.flash_messages')
    <!-- username field -->
    <div class="login-dialog">
        <div class="dialog-header">
            <div class="navbar-logo-image">
                <img src="/vendor/bootleg/cms/img/cms.png" />
            </div>
            <div class="navbar-title">
                <span>Native Laravel Open-Source</span>
                <span>Content Management System</span>
            </div>
        </div>
        {!! Form::open(array('class'=>'form-signin')) !!}
            <div class="form-group">
                {!! Form::label('email', 'Email') !!}
                {!! Form::text('email',null, array('placeholder'=>'Email Address','class'=>'form-control')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('password', 'Password') !!}
                {!! Form::password('password', array('placeholder'=>'Password','class'=>'form-control')) !!}
            </div>

            <button class="btn btn-lg btn-cms-primary btn-block"><span>Login</span> <i class="glyphicon glyphicon-log-in"></i></button>
            <!-- password field -->
            <!-- submit button -->
        {!! Form::close() !!}
    </div>
@stop
