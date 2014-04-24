<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">BootlegCMS</a>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
                <li>
                    <a href="#">Home</a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Applications <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        @foreach($applications as $app)
                        <li><a href="#">{{$app->name}}</a></li>  
                        @endforeach
                      
                    </ul>
                </li>
        </ul> <!-- / .navbar-nav -->
      </div>
    </div>
</div>
