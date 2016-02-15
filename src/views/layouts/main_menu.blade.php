<div class="col-sm-3 col-md-2 sidebar">
    <ul class="nav nav-sidebar">
        <li class="{{Request::is(config('bootlegcms.cms_route').'users/dashboard*')?'active':''}}">
            <a href="{{action('\Bootleg\Cms\UsersController@anyDashboard', array())}}">
                <i class="menu-icon glyphicon glyphicon-home"></i>
                <span class="mm-text">Dashboard</span>
            </a>
        </li>
        @if(Permission::getPermission('Bootleg\Cms\ContentsController@anyIndex','')->result)
        <li class="{{Request::is(config('bootlegcms.cms_route').'content/*')?'active':''}}">
            <a href="{{action('\Bootleg\Cms\ContentsController@anyIndex', array())}}">
                <i class="menu-icon glyphicon glyphicon-list-alt"></i>
                <span class="mm-text">Content</span>
            </a>
        </li>
        @endif
        @if(Permission::getPermission('Bootleg\Cms\UsersController@anyIndex','')->result)
        <li class="{{Request::is(config('bootlegcms.cms_route').'users/*')?'active':''}}">
            <a href="{{action('\Bootleg\Cms\UsersController@anyIndex', array())}}">
                <i class="menu-icon glyphicon glyphicon-user"></i>
                <span class="mm-text">Users</span>
            </a>
        </li>
        @endif
        <?php
            $menuItems = Event::fire('menu.links', array());
        ?>
        @foreach($menuItems as $menuItem)
            <?php
            $loc = @$menuItem['href']?$menuItem['href']:action($menuItem['location']);
            ?>
        @if(Permission::getPermission($menuItem['location'],'')->result)
        <li class="{{Request::is(config('bootlegcms.cms_route').$menuItem['activePattern'])?'active':''}}">
            <a href="{{$loc}}">
                <i class="menu-icon glyphicon {{$menuItem['icon']}}"></i>
                <span class="mm-text">{{$menuItem['title']}}</span>
            </a>
        </li>
        @endif
        @endforeach
        @if(Permission::getPermission('\Bootleg\Cms\ApplicationController@anySettings','')->result)
        <li class="{{Request::is(config('bootlegcms.cms_route').'application/settings*')?'active':''}}">
            <a href="{{action('\Bootleg\Cms\ApplicationController@anySettings', array())}}">
                <i class="menu-icon glyphicon glyphicon-cog"></i>
                <span class="mm-text">Settings</span>
            </a>
        </li>
        @endif
        <li class="text-center menu-additional">
            <?php
                $application = Application::getApplication();
            ?>
            <h2>{{@$application->name}}</h2>
            <div class="btn-group">
                <a title='Logout' href="{{action('\Bootleg\Cms\UsersController@anyLogout', array())}}" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-log-out"></span></a>
                <a title='Dashboard' href="{{action('\Bootleg\Cms\UsersController@anySettings', array())}}" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-home"></span></a>
                <a title='My Settings' href="{{action('\Bootleg\Cms\UsersController@anySettings', array())}}" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-user"></span></a>
            </div>
        </li>
    </ul>
</div>
