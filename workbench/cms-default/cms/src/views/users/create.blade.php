@extends('cms::layouts.scaffold')


@section('main')
<div class="col-md-10 fullheight">

<h1>Create user</h1>

{{ Form::open(array('action' => 'UsersController@anyStore')) }}
    <ul>
        <li>
            {{ Form::label('username', 'username:') }}
            {{ Form::text('username') }}
        </li>

        <li>
            {{ Form::label('password', 'Password:') }}
            {{ Form::text('password') }}
        </li>

        <li>
            {{ Form::label('email', 'Email:') }}
            {{ Form::text('email') }}
        </li>

        <li>
            {{ Form::label('role_id', 'Role_id:') }}
            {{ Form::text('role_id') }}
        </li>

        <li>
            {{ Form::label('status', 'Status:') }}
            {{ Form::text('status') }}
        </li>

        <li>
            {{ Form::submit('Submit', array('class' => 'btn')) }}
        </li>
    </ul>
{{ Form::close() }}

@if ($errors->any())
    <ul>
        {{ implode('', $errors->all('<li class="error">:message</li>')) }}
    </ul>
@endif
</div>
@stop


