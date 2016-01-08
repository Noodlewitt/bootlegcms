<?php
    $application = Application::getApplication();
    $menuItems = Event::fire('menu.links', []);
?>

<div id="side-nav" class="col-sm-3 col-md-2 sidebar">
    <!--
    <div class="sidebar-header row">
        <div class="sidebar-avatar-image">
            --><!-- <img src="/vendor/bootleg/cms/img/madeinkatana.png" />--><!--
            <img src="{{ 'http://www.gravatar.com/avatar/'.md5(strtolower(trim(@Auth::user()->email))).'jpg?d=identicon' }}" />
        </div>
        <div class="sidebar-user-summary">
            <div class="text-bold">Welcome</div>
            <div>{{ @Auth::user()->name ? Auth::user()->name : (@Auth::user()->username ? Auth::user()->username : 'Administrator') }}</div>
        </div>
    </div>
    -->
    <div class="sidebar-menu-items">
        <a href="{{ action('\Bootleg\Cms\UsersController@anyDashboard') }}" class="{{ Request::is(config('bootlegcms.cms_route') . 'users/dashboard*') ? 'active' : '' }}">
            <i class="menu-icon glyphicon glyphicon-home"></i>
            <span class="mm-text">Home</span>
        </a>
        @if(Permission::getPermission('Bootleg\Cms\ContentsController@anyIndex','')->result)
            <a href="{{ action('\Bootleg\Cms\ContentsController@anyIndex') }}" class="{{ Request::is(config('bootlegcms.cms_route') . 'content*') ? 'active' : '' }}">
                <i class="menu-icon glyphicon glyphicon-list-alt"></i>
                <span class="mm-text">Content</span>
            </a>
        @endif
        @if(Permission::getPermission('Bootleg\Cms\UsersController@anyIndex','')->result)
            <a href="{{ action('\Bootleg\Cms\UsersController@anyIndex') }}" class="{{ Request::is(config('bootlegcms.cms_route') . 'users*') && !Request::is(config('bootlegcms.cms_route') . 'users/dashboard*') ? 'active' : '' }}">
                <i class="menu-icon glyphicon glyphicon-user"></i>
                <span class="mm-text">Users</span>
            </a>
        @endif
        @foreach($menuItems as $menuItem)
            @if(Permission::getPermission($menuItem['location'],'')->result)
                <a href="{{ action($menuItem['location']) }}" class="{{ Request::is(config('bootlegcms.cms_route') . $menuItem['activePattern']) ? 'active' : '' }}">
                    <i class="menu-icon glyphicon {{ $menuItem['icon'] }}"></i>
                    <span class="mm-text">{{ $menuItem['title'] }}</span>
                    @if(isset($menuItem['badge']))
                        <span class="badge">{{ $menuItem['badge'] }}</span>
                    @endif
                </a>
            @endif
        @endforeach
        @if(Permission::getPermission('\Bootleg\Cms\ApplicationController@anySettings','')->result)
            <a href="{{ action('\Bootleg\Cms\ApplicationController@anySettings') }}" class="{{ Request::is(config('bootlegcms.cms_route') . 'application/settings*') ? 'active' : '' }}">
                <i class="menu-icon glyphicon glyphicon-cog"></i>
                <span class="mm-text">Settings</span>
            </a>
        @endif
    </div>
    <!--
    <div class="text-center sidebar-menu-additional">
        <a title='Logout' href="{{ action('\Bootleg\Cms\UsersController@anyLogout') }}"
               class="btn btn-cms-primary-inverse btn-block"><span>Logout</span> <i class="text-cms-primary glyphicon glyphicon-log-out"></i></a>
    </div>
    -->
</div>
