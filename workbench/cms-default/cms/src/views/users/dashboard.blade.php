@extends('cms::layouts.scaffold')

@section('main')
<div id="content-wrapper">
    <div class="page-header">
			
        <div class="row">
            <!-- Page header, center on small screens -->
            <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Dashboard</h1>

        </div>
    </div>
    <h2>Username: {{Auth::user()->username}}</h2>
    <h2>Email: {{Auth::user()->email}}</h2>
    ..more info about you.
</div>
@stop
