<div id="main-menu" role="navigation" class="">
    <div id="main-menu-inner">
        <ul class="navigation">

            <li class="{{Request::is(Utils::cmsRoute.'users/dashboard*')?'active':''}}">
                <a href="{{action('UsersController@anyDashboard', array())}}">
                    <i class="menu-icon fa fa-dashboard"></i>
                    <span class="mm-text">Dashboard</span>
                </a>
            </li>
            <li class="{{Request::is(Utils::cmsRoute.'content/*')?'active':''}}">
                <a href="{{action('ContentsController@anyIndex', array())}}">
                    <i class="menu-icon fa fa-th-large"></i>
                    <span class="mm-text">Content</span>
                </a>
            </li>
            <li class="{{Request::is(Utils::cmsRoute.'users/index*')?'active':''}}">
                <a href="{{action('UsersController@anyIndex', array())}}">
                    <i class="menu-icon fa fa-users"></i>
                    <span class="mm-text">Users</span>
                </a>
            </li>
            <li class="{{Request::is(Utils::cmsRoute.'application/settings*')?'active':''}}">
                <a href="{{action('ApplicationController@anySettings', array())}}">
                    <i class="menu-icon fa fa-cogs"></i>
                    <span class="mm-text">Settings</span>
                </a>
            </li>
            <li>
                <a href="{{action('UsersController@anyLogout', array())}}">
                    <i class="menu-icon fa fa-power-off"></i>
                    <span class="mm-text">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>