@extends('cms::layouts.scaffold')

@section('main')
<div id="content-wrapper">
    <div class="page-header">	
        <div class="row">
            <!-- Page header, center on small screens -->
            <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-th-large page-header-icon"></i>&nbsp;&nbsp;Content</h1>
            <a href="http://herc/cms/content/create" class="btn btn-primary pull-right">Create Page</a>
        </div>
    </div>
    <div class="col-md-2">
        @include('cms::contents.tree', array('content'=>@$content, 'tree'=>@$tree))
    </div>
    <div class="col-md-10">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#home" data-toggle="tab">Home</a></li>
            <li><a href="#permissions" data-toggle="tab">Permissions</a></li>
            <li><a href="#advanced" data-toggle="tab">Advanced</a></li>
        </ul>
        <div class="tab-content">
            @section('content')
                <div class="tab-pane active" id="home">
                    @include('cms::contents.form', array('content'=>@$content))
                </div>
                <div class="tab-pane" id="permissions">
                    @include('cms::contents.permission', array('content'=>@$content))
                </div>
                <div class="tab-pane" id="advanced">
                    TODO: move advanced options in here.
                </div>
            @show
        </div>
    </div>
    
</div>
@stop
