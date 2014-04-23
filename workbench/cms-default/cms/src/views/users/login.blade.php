@extends('cms::layouts.bare')

@section('main')
{{ Form::open(array('controller' => 'users@anyLogin')) }}
    <!-- username field -->
    <p>{{ Form::label('email', 'Email') }}</p>
    <p>{{ Form::text('email') }}</p>
    <!-- password field -->
    <p>{{ Form::label('password', 'Password') }}</p>
    <p>{{ Form::password('password') }}</p>
    <!-- submit button -->
    <p>{{ Form::submit('Login') }}</p>
{{ Form::close() }}

@stop