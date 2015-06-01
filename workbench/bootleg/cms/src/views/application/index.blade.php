@extends($cms_package.'::layouts.master')
@section('main-content')
    <div class="col-md-offset-2 col-md-10 main-content">
        <div class="page-header row">
            <!-- Page header, center on small screens -->
            <h1 class="col-xs-12"><i class="glyphicon glyphicon-book"></i>&nbsp;&nbsp;Applications</h1>
        </div>
        @if ($applications->count())
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Application ID</th>
                        <th>Application Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($applications as $application)
                        <tr>
                            <td>{{{ $application->id }}}</td>
                            <td>{{{ $application->name }}}</td>
                            <td>
                                {{ Form::open(array('method' => 'DELETE', 'action' => array('ApplicationController@deleteDestroy', $application->id))) }}
                                    <a href='{{action('ApplicationController@getView', array('id'=>$application->id))}}' class='btn btn-warning btn-xs'>View Applicaiton</a>
                                    {{ Form::submit('Delete', array('class' => 'btn btn-danger btn-xs')) }}
                                {{ Form::close() }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{$applications->links()}}
        @else
            There are no applicaitons
        @endif
    </div>
@stop