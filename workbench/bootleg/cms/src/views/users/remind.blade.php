<form action="{{ action('RemindersController@postRemind') }}" class='form-signin' method="POST">
    <h2 class="form-signin-heading text-center">Bootleg CMS</h2>
    <h3 class="form-signin-heading text-center">Send Password Reminder</h3>
    {{ Form::label('email', 'Email') }}
    {{ Form::text('email',null, array('placeholder'=>'Email Address','class'=>'form-control')) }}
    {{ Form::label('send_reminder', 'Send Reminder') }}
    {{ Form::submit('Send Reminder', array('class'=>'btn btn-lg btn-primary btn-block')) }}
</form>