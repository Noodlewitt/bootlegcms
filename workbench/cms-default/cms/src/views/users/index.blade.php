@extends('cms::layouts.scaffold')


@section('main')
<div id="content-wrapper">
<div class="page-header">	
    <div class="row">
        <!-- Page header, center on small screens -->
        <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-users page-header-icon"></i>&nbsp;&nbsp;Users</h1>
        {{ link_to_action('UsersController@anyCreate', 'Create User', null, array('class'=>'btn btn-primary pull-right')) }}
    </div>
</div>

{{ link_to_action('UsersController@anyCreate', 'Create User') }}

@if ($users->count())
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>username</th>
                <th>Email</th>
                <th>Role_id</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{{ $user->username }}}</td>
                    <td>{{{ $user->email }}}</td>
                    <td>{{{ $user->role_id }}}</td>
                    <td>{{{ $user->status }}}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
@else
    There are no users
@endif
</div>
@stop