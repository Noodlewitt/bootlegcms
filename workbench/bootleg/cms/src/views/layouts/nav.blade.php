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
            @if(count($applications) > 1 || Permission::getPermission('ApplicationController@anyCreate','')->result)

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Applications <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    @foreach($applications as $app)
                        @if(@$app->url[0])
                        <li><a href="{{$app->url[0]->protocol or 'http://'}}{{$app->url[0]->domain}}{{$app->url[0]->folder}}{{Utils::cmsRoute}}">{{$app->name}}</a></li>
                        @endif
                    @endforeach
                    @if(Permission::getPermission('ApplicationController@anyCreate','')->result)
                        <li role="presentation" class="divider"></li>
                        <li><a href="{{action('ApplicationController@anyCreate')}}">Create Application</a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if(count($application->languages))
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Languages <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    @foreach($application->languages as $lang)
                        <li><a href="{{Applicationurl::getBaseUrl().Utils::cmsRoute.$lang->code}}">{{$lang->name}}</a></li>
                    @endforeach
                </ul>
            </li>
            @endif

            <?php
                $navItems = Event::fire('nav.links', array());
            ?>
            @foreach($navItems as $navItem)
                <a href="{{action($navItem['location'])}}">{{$navItem['title']}}</a>
            @endforeach
        </ul> <!-- / .navbar-nav -->
      </div>
    </div>
</div>
