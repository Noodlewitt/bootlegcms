@extends('cms::layouts.scaffold')

@section('main')
<div class="col-md-10 fullheight">
    <h1>This is the dashboard</h1>
    <h2>Username: {{Auth::user()->username}}</h2>
    <h2>Email: {{Auth::user()->email}}</h2>
</div>
@stop
