<?php
    $navItems = Event::fire('nav.links', []);
?>

<div class="navbar navbar-fixed-top" role="navigation">
    <div class="navbar-sidebar-collapse">

    </div>
    <div class="navbar-logo-image">
        <img src="/vendor/bootleg/cms/img/cms.png" />
    </div>
    <div class="navbar-title">
        <span>Native Laravel Open-Source</span>
        <span>Content Management System</span>
    </div>

    <!--<div class="navbar-menu-item">
        <a href="#">Home</a>
    </div>-->

    @if(count($applications) > 1 || Permission::getPermission('\Bootleg\Cms\ApplicationController@anyCreate','')->result)
        <div class="navbar-menu-item dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ config('bootlegcms.cms_application_title') }} <i class="fa fa-chevron-down"></i></a>
            <ul class="dropdown-menu">
                @foreach($applications as $app)
                    @if(@$app->url[0])
                        <li><a href="{{ action('\Bootleg\Cms\ApplicationController@getSwitch', [$app->id]) }}">{{$app->name}}</a></li>
                    @endif
                @endforeach
                @if(Permission::getPermission('\Bootleg\Cms\ApplicationController@anyCreate','')->result)
                    <li role="presentation" class="divider"></li>
                    <li><a href="{{ action('\Bootleg\Cms\ApplicationController@anyCreate') }}">Create Application</a></li>
                @endif
            </ul>
        </div>
    @endif

    @if(count($application->languages))
        <div class="navbar-menu-item dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Languages <i class="fa fa-chevron-down"></i></a>
            <ul class="dropdown-menu">
                @foreach($application->languages as $lang)
                    <li><a href="{{ Applicationurl::getBaseUrl().config('bootlegcms.cms_route') . $lang->code }}">{{$lang->name}}</a></li>
                @endforeach
            </ul>
        </div>
    @endif

    @foreach($navItems as $navItem)
        <div class="navbar-menu-item">
            <a href="{{ action($navItem['location']) }}">{{ $navItem['title'] }}</a>
        </div>
    @endforeach
</div>
