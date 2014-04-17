<div class="col-md-2 fullheight mainmenu">
    <ul class="nav nav-pills nav-stacked">
        <li class="active">{{ link_to_action('UsersController@anyDashboard', 'Dashboard', null, array()) }}</li>
        <li>{{ link_to_action('ContentsController@anyIndex', 'Content', null, array()) }}</li>
        <li>{{ link_to_action('UsersController@anyIndex', 'Users', null, array()) }}</li>
        <li>{{ link_to_action('ApplicationController@anySettings', 'Settings', null, array()) }}</li>
        <li>{{ link_to_action('UsersController@anyLogout', 'Logout', null, array()) }}</li>
    </ul>

</div>