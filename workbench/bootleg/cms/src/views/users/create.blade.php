<div class="col-md-offset-2 col-md-10">
    <div class="page-header row">   
            <!-- Page header, center on small screens -->
            <h1 class="col-xs-12"><i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;Create User</h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {{ Form::open(array('action' => 'UsersController@anyStore', 'class'=>'main-form')) }}
                <ul>
                    <li class="form-group">
                        {{ Form::label('username', 'Username:') }}
                        {{ Form::text('username', null, array('class'=>'form-control')) }}
                    </li>

                    <li class="form-group js-send-email">
                        <label>Send Email:</label>
                        <div class="radio">
                            <label>
                                {{ Form::radio('send_email','0','') }}
                                Disabled
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                {{ Form::radio('send_email','1','') }}
                                Enabled
                            </label>
                        </div>
                        <p>Sends an email to user with instructions to set password.</p>
                    </li>

                    <li class="form-group js-password">
                        {{ Form::label('password', 'Password:') }}
                        {{ Form::password('password', array('class'=>'form-control')) }}
                    </li>

                    <li class="form-group js-password">
                        {{ Form::label('password_confirm', 'Password Again:') }}
                        {{ Form::password('password_confirm', array('class'=>'form-control')) }}
                    </li>

                    <li class="form-group">
                        {{ Form::label('email', 'Email:') }}
                        {{ Form::text('email', null, array('class'=>'form-control')) }}
                    </li>

                    <li class="form-group">
                        {{ Form::label('role_id', 'Role:') }}
                        {{ Form::select('role_id', $roles , Input::old('role_id'), array('class'=>'form-control')) }}
                    </li>

                    <li class="form-group">
                        <label>Status:</label>
                        <div class="radio">
                            <label>
                                {{ Form::radio('status','0','') }}
                                Disabled
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                {{ Form::radio('status','1','') }}
                                Enabled
                            </label>
                        </div>
                    </li>

                    <li class="form-group">
                        <div class='btn-group btn-group-lg'>
                            {{ Form::submit(@$content->id?'Update':'Create', array('class' => 'btn btn-success')) }}
                            {{ link_to_action('ContentsController@anyEdit', 'Cancel', @$content->id, array('class' => 'btn btn-danger')) }}
                        </div>
                    </li>
                </ul>
            {{ Form::close() }}

            @if ($errors->any())
                <ul>
                    {{ implode('', $errors->all('<li class="error">:message</li>')) }}
                </ul>
            @endif
        </div>
    </div>
</div>
<script>
    $(function() {
        $('.js-password').hide();
        $('.js-send-email input').change(function(){
            
            if($(this).val() == 1){
                $('.js-password').fadeOut();
            }
            else{
                $('.js-password').fadeIn();   
            }
        });
    });
</script>


