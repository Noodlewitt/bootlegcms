@extends(view()->exists($cms_package.'::layouts.master') ? $cms_package.'::layouts.master' : 'cms::layouts.master')
@section('main-content')
    <div class="col-md-offset-2 col-md-10">
        <div class="page-header row">   
                <!-- Page header, center on small screens -->
                <h1 class="col-xs-12"><i class="glyphicon glyphicon-cog"></i>&nbsp;&nbsp;View Application</h1>
        </div>
        
        
        <div class="row">
            <div class="col-xs-12">
                <h2>{{$application->name}}</h2>
                <p>URLs:</p>
                <ul>
                @foreach($application->url()->get() as $url)
                    <li><a href="//{{$url->domain}}{{$url->folder}}">//{{$url->domain}}{{$url->folder}}</a></li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
@stop
