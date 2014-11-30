<div class="col-xs-6 col-sm-3 ">
    <div class='panel panel-primary'>
        <div class="panel-heading">
            <h3 class="panel-title">Logged in as {{$user->username}}</h3>
        </div>
        <div class="panel-body">
            <ul>
                <li>Access: {{$user->role()->first()->name}}</li>
                <li>Last Login: {{date('d F Y',strtotime($user->loggedin_at))}} at {{date('G:i:s',strtotime($user->loggedin_at))}}</li>
            </ul>
            <?php
            $cont = $user->author()->first();
            ?>
            <span class="text-muted">Last edited: {{$cont->name or "none"}}</span>
        </div>
    </div>
</div>