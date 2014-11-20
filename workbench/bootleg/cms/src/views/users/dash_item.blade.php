<div class="col-xs-6 col-sm-3 placeholder">
    <h3>Logged in as</h3>
    <ul>
        <li>Username: {{$user->username}}</li>
        <li>Role: {{$user->role()->first()->name}}</li>
        <li>Last Login: {{date('d F Y',strtotime($user->loggedin_at))}} at {{date('G:i:s',strtotime($user->loggedin_at))}}</li>
    </ul>
    <span class="text-muted">User details..</span>
</div>