<div class="col-xs-6 col-sm-3 placeholder">
    <h3>Application</h3>
    <ul>
        <li>Name: {{$application->name}}</li>
        <li>domain: {{$application->url()->first()->domain}}</li>
    </ul>
    <span class="text-muted">Application</span>
</div>