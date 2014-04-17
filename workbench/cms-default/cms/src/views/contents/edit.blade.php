@extends('cms::layouts.scaffold')

@section('main')
<div class="col-md-2 fullheight">
@include('cms::contents.tree', array('content'=>@$content, 'tree'=>@$tree))
</div>
<div class="col-md-8 fullheight">
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
@stop

<script>
    $(function() {
        $('ul.nav-tabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show')
        });
    });
</script>