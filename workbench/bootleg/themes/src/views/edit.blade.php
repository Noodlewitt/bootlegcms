@extends('cms::layouts.master')
@section('main-content')
<div class="col-md-offset-2 col-md-10">
    <div class="page-header row">   
            <!-- Page header, center on small screens -->
            <h1 class='col-xs-12' class="col-xs-12"><i class="glyphicon glyphicon-wrench"></i>&nbsp;&nbsp;Themes</h1>
    </div>
    <div class='row'>
        {{ Form::model($theme, array('method' => 'POST', 'files'=>true, 'class'=>'main-form col-xs-12', 'action' => array('Bootleg\Themes\ThemesController@postEdit', @$theme->id))) }}
        <ul >
            @foreach($settings as $setting)
                <li class="form-group">
                    @include('cms::contents.input_types.'.$setting[0]->field_type, array('setting'=>$setting))
                </li>
            @endforeach

            <li class="form-group">
                <div class='btn-group btn-group-lg'>

                    {{ Form::submit('Update', array('class' => 'btn btn-success ')) }}
                    {{ link_to_action('Bootleg\Themes\ThemesController@getEdit', 'Cancel', @$post->id, array('class' => 'btn btn-danger ')) }}
                </div>
            </li>

        </ul>

    </div>
</div>
@stop