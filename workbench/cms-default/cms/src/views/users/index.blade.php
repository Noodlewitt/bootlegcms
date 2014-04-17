@extends('cms::layouts.scaffold')


@section('main')
<div class="col-md-10 fullheight">
<h1>All users</h1>

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