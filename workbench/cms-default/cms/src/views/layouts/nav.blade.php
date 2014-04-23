<div id="main-navbar" class="navbar navbar-inverse" role="navigation">
        <!-- Main menu toggle -->
        <button type="button" id="main-menu-toggle"><i class="navbar-icon fa fa-bars icon"></i><span class="hide-menu-text">HIDE MENU</span></button>

        <div class="navbar-inner">
                <!-- Main navbar header -->
                <div class="navbar-header">

                        <!-- Logo -->
                        <a href="{{action('UsersController@anyDashboard', array())}}" class="navbar-brand">
                                Bootleg CMS
                        </a>

                        <!-- Main navbar toggle -->
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse"><i class="navbar-icon fa fa-bars"></i></button>

                </div> <!-- / .navbar-header -->

                <div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
                        <div>
                                <ul class="nav navbar-nav">
                                        <li>
                                            <a href="#">Home</a>
                                        </li>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Applications</a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">First item</a></li>
                                                <li><a href="#">Second item</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">Third item</a></li>
                                            </ul>
                                        </li>
                                </ul> <!-- / .navbar-nav -->

                                <div class="right clearfix">
                                        <ul class="nav navbar-nav pull-right right-navbar-nav">



                                                <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle user-menu" data-toggle="dropdown">
                                                                <img src="assets/demo/avatars/1.jpg" alt="">
                                                                <span>{{@Auth::user()->username}}</span>
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                                <li><a href="#">Profile <span class="label label-warning pull-right">new</span></a></li>
                                                                <li><a href="#">Account <span class="badge badge-primary pull-right">new</span></a></li>
                                                                <li><a href="#"><i class="dropdown-icon fa fa-cog"></i>&nbsp;&nbsp;Settings</a></li>
                                                                <li class="divider"></li>
                                                                <li><a href="pages-signin.html"><i class="dropdown-icon fa fa-power-off"></i>&nbsp;&nbsp;Log Out</a></li>
                                                        </ul>
                                                </li>
                                        </ul> <!-- / .navbar-nav -->
                                </div> <!-- / .right -->
                        </div>
                </div> <!-- / #main-navbar-collapse -->
        </div> <!-- / .navbar-inner -->
</div>