@extends('cms::layouts.master')
@section('main-content')
<div class="col-md-offset-2 col-md-10">
    <div class="page-header row">   
            <!-- Page header, center on small screens -->
            <h1 class='col-xs-12' class="col-xs-12"><i class="glyphicon glyphicon-wrench"></i>&nbsp;&nbsp;Themes</h1>
    </div>
    <div class='row'>
        <div class="col-xs-12">
            <a class='btn btn-primary' href='{{action('Bootleg\Themes\ThemesController@postEdit', array('id'=>$currentTheme->id))}}'>Edit Active Theme</a>
        </div>
        <div class="col-xs-12">
        @foreach($themes as $theme)
            <div class='col-md-4'>
                <div class="panel panel-default ">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{$theme->name}}</h3>
                    </div>
                    <div class="panel-body">
                        <img src='{{$theme->preview}}' style='max-width:100%' alt='{{$theme->name}}'/>
                    </div>
                    <div class="panel-footer">
                        <a class='btn btn-primary' href='{{action('Bootleg\Themes\ThemesController@getSetTheme', array($theme->id))}}'>Activate Theme</a>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
</div>
@stop