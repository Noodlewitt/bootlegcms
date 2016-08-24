@extends(view()->exists($cms_package.'::layouts.bare') ? $cms_package.'::layouts.bare' : 'cms::layouts.bare')
@section('main-content')
@include('cms::layouts.flash_messages')
{!! Form::open(array('class'=>'form-signin')) !!}
    <!-- username field -->
    <h2 class="form-signin-heading text-center">{{$application->name}} <br /> Bootleg CMS</h2>
    {!! Form::label('email', 'Email') !!}
    {!! Form::text('email',null, array('placeholder'=>'Email Address','class'=>'form-control')) !!}
    <!-- password field -->
    {!! Form::label('password', 'Password') !!}
    {!! Form::password('password', array('placeholder'=>'Password','class'=>'form-control')) !!}
    <!-- submit button -->
    {!! Form::label('login', 'Login') !!}
    {!! Form::submit('Login', array('class'=>'btn btn-lg btn-primary btn-block')) !!}
{!! Form::close() !!}   
@stop