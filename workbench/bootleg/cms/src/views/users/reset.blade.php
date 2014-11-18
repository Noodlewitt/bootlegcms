<form action="{{ action('RemindersController@postReset') }}" class='form-signin' method="POST">
    <h2 class="form-signin-heading text-center">Bootleg CMS</h2>
    <h3 class="form-signin-heading text-center">Reset Password</h3>
    <input type="hidden" name="token" value="{{ $token }}">
    {{ Form::label('email', 'Email') }}
    {{ Form::text('email',null, array('placeholder'=>'Email Address','class'=>'form-control')) }}
    {{ Form::label('password', 'Password') }}
    {{ Form::text('password',null, array('class'=>'form-control')) }}
    {{ Form::label('password_confirmation', 'Password Confirmation') }}
    {{ Form::text('password_confirmation',null, array('class'=>'form-control')) }}
    {{ Form::label('reset_password', 'Reset Password') }}
    {{ Form::submit('Reset Password', array('class'=>'btn btn-lg btn-primary btn-block')) }}
</form>