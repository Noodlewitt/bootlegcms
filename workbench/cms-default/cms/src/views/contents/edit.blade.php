    <div class='overlay'></div>
    <div class="page-header row">
        <!-- Page header, center on small screens -->
        <h1 class="col-xs-12"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;&nbsp;Content {{ link_to_action('ContentsController@anyCreate', 'Create Content', null, array('class'=>'btn btn-primary pull-right')) }}</h1>
    </div>
    
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
