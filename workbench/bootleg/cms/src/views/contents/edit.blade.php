    <div class='overlay'></div>
    <div class="page-header row">
        <!-- Page header, center on small screens -->
        <h1 class="col-xs-12"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;&nbsp;Content</h1>
    </div>
    
    @include($content->edit_package.'::'.$content->edit_view, array('content'=>@$content))