@extends(view()->exists($cms_package.'::layouts.master') ? $cms_package.'::layouts.master' : 'cms::layouts.master')
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
                        <th>Application Primary URL</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($applications as $application)
                        <tr>
                            <td>{{ $application->id }}</td>
                            <td>{{ $application->name }}</td>
                            <td>{{$application->url[0]->domain.$application->url[0]->folder}} </td>
                            <td>
                                {!! Form::open(array('method' => 'DELETE', 'class'=>'btn-group-xs btn-group', 'action' => array('\Bootleg\Cms\ApplicationController@deleteDestroy', $application->id))) !!}
                                    <a href='{{action('\Bootleg\Cms\ApplicationController@getView', array('id'=>$application->id))}}' class='btn btn-success btn-xs'>View Applicaiton</a>
                                    <a href='//{{$application->url[0]->domain.$application->url[0]->folder}}' class='btn btn-info btn-xs'>Go To Applicaiton</a>
                                    <a href='//{{$application->url[0]->domain.$application->url[0]->folder}}cms' class='btn btn-primary btn-xs'>Go To Applicaiton CMS</a>
                                    {!! Form::submit('Delete', array('class' => 'btn btn-danger btn-xs')) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{$applications->render()}}
        @else
            There are no applicaitons
        @endif
    </div>
@stop