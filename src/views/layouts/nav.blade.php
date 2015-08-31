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
                <a href="/{{config('bootlegcms.cms_route')}}">{{trans('cms::messages.menu.home')}}</a>
            </li>
            @if(count($applications) > 1 || Permission::getPermission('\Bootleg\Cms\ApplicationController@anyCreate','')->result)

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{trans('cms::messages.menu.applications')}} <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    @foreach($applications as $app)
                        @if(@$app->url[0])
                        <li><a href="{{$app->url[0]->protocol or 'http://'}}{{$app->url[0]->domain}}{{$app->url[0]->folder}}{{config('bootlegcms.cms_route')}}">{{$app->name}}</a></li>
                        @endif
                    @endforeach
                    @if(Permission::getPermission('\Bootleg\Cms\ApplicationController@anyCreate','')->result)
                        <li role="presentation" class="divider"></li>
                        <li><a href="{{action('\Bootleg\Cms\ApplicationController@anyCreate')}}">Create Application</a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if(count($application->languages))
            <li class="dropdown">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-haspopup="true" id="application-dropdown" data-toggle="dropdown">{{trans('cms::messages.menu.language')}} <b class="caret"></b></a>
                <ul class="dropdown-menu" aria-labelledby="application-dropdown">
                    @foreach($application->languages as $lang)
                        @if($lang->code == $application->default_locale)
                            <li><a  href="{{Applicationurl::getBaseUrl().config('bootlegcms.cms_route')}}">{{$lang->name}}</a></li>
                        @else
                            <li><a href="{{Applicationurl::getBaseUrl().config('bootlegcms.cms_route')}}{{$lang->code}}">{{$lang->name}}</a></li>
                        @endif
                    @endforeach
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Set Current Language As My Default</a></li>
                            <li><a href="#">Add Language</a></li>
                </ul>
            </li>
            @endif

            <li class="align-right">
                {{\App::getLocale()}}
            </li>

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
