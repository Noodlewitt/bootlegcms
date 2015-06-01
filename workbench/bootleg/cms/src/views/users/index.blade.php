@extends($cms_package.'::layouts.master')
@section('main-content')
<div class="col-md-offset-2 col-md-10">
    <div class="page-header row">	
            <!-- Page header, center on small screens -->
            <h1 class="col-xs-12"><i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;Users {{ link_to_action('UsersController@anyCreate', 'Create User', null, array('class'=>'btn btn-primary pull-right')) }}</h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
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
        </div>
    @else
        There are no users
    @endif
    </div>
</div>
@stop