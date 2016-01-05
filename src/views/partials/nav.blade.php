<?php
$navItems = Event::fire('nav.links', []);
?>

<div class="navbar navbar-fixed-top navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <div class="navbar-sidebar-collapse">

            </div>
            @include(view()->exists($cms_package.'::partials.logo') ? $cms_package.'::partials.logo' : 'cms::partials.logo')
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-left">

                @if(count($applications) > 1 || Permission::getPermission('\Bootleg\Cms\ApplicationController@anyCreate','')->result)
                    <li class="navbar-menu-item dropdown">
                        <a href="#" class="dropdown-toggle"
                           data-toggle="dropdown">{{ config('bootlegcms.cms_application_title') }} <i
                                    class="fa fa-chevron-down"></i></a>
                        <ul class="dropdown-menu">
                            @foreach($applications as $app)
                                @if(@$app->url[0])
                                    <li>
                                        <a href="{{ $app->url[0]->protocol or 'http://'}}{{ $app->url[0]->domain . $app->url[0]->folder . config('bootlegcms.cms_route') }}">{{ $app->name }}</a>
                                    </li>
                                @endif
                            @endforeach
                            @if(Permission::getPermission('\Bootleg\Cms\ApplicationController@anyCreate','')->result)
                                <li role="presentation" class="divider"></li>
                                <li><a href="{{ action('\Bootleg\Cms\ApplicationController@anyCreate') }}">Create
                                        Application</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(count($application->languages))
                    <li class="navbar-menu-item dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Languages <i
                                    class="fa fa-chevron-down"></i></a>
                        <ul class="dropdown-menu">
                            @foreach($application->languages as $lang)
                                <li>
                                    <a href="{{ Applicationurl::getBaseUrl().config('bootlegcms.cms_route') . $lang->code }}">{{$lang->name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif

                @foreach($navItems as $navItem)
                    <li class="navbar-menu-item">
                        <a href="{{ action($navItem['location']) }}">{{ $navItem['title'] }}</a>
                    </li>
                @endforeach

            </ul>

            <!--<div class="navbar-menu-item">
                <a href="#">Home</a>
            </div>-->
            <ul class="nav navbar-nav navbar-right">
                <li class="navbar-user">
                    <div class="navbar-user-avatar">
                        <!-- <img src="/vendor/bootleg/cms/img/madeinkatana.png" />-->
                        <img src="{{ 'http://www.gravatar.com/avatar/'.md5(strtolower(trim(@Auth::user()->email))).'jpg?d=identicon' }}"/>
                    </div>
                    <div class="navbar-user-summary">
                        <!--<div class="text-bold">Welcome</div>-->
                        <div>{{ @Auth::user()->name ? Auth::user()->name : (@Auth::user()->username ? Auth::user()->username : 'Administrator') }}</div>
                    </div>
                    <a title='Logout' href="{{ action('\Bootleg\Cms\UsersController@anyLogout') }}" class="logout-button">
                        <span>Logout</span> <i class="text-cms-primary glyphicon glyphicon-log-out"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
