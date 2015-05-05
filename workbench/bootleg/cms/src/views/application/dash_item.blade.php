<div class="col-xs-6 col-sm-3 ">
    <div class='panel panel-info'>
        <div class="panel-heading">
            <h3 class="panel-title">Application</h3>
        </div>
        <div class="panel-body">
            <ul>
                <li>Name: {{$application->name}}</li>
                <li>domain: {{$application->url()->first()->domain}}</li>
            </ul>
        </div>
    </div>
</div>