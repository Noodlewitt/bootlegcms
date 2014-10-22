<h1>{{@$content->id?'Update':'Create'}} {{$content->name or 'Content'}}</h1>
@include('cms::layouts.flash_messages')
@if($permission->result === false)
    <div class="alert alert-warning">
        <p>You do not have permission to edit this content item.</p>
        <p>{{$permission->picked->comment}}</p>
    </div>
@endif
<ul class="nav nav-tabs">
    <?php $i = 0; $advanced = false; $contentSection = false?>

    @foreach($settings as $key=>$section)
        <?php
        if($key == 'Advanced'){
            $advanced = true;
        }
        if($key == 'Content'){
            $contentSection = true;
        }
        ?>
        <li class='{{$i==0?"active":""}}'><a href="#tab-{{$key}}" data-toggle="tab">{{$key}}</a></li>
        <?php $i++; ?>
    @endforeach
    @if(!$contentSection)
        <li class="active"><a href="#tab-Content" data-toggle="tab">Content</a></li>
    @endif
    @if(!$advanced)
        <li><a href="#tab-Advanced" data-toggle="tab">Advanced</a></li>
    @endif
        <li><a href="#tab-Permission" data-toggle="tab">Permisssions</a></li>
</ul>
@if($content_mode == 'template')
{{ Form::model($content, array('method' => 'POST', 'files'=>true, 'class'=>'main-form', 'action' => array('TemplateController@anyUpdate', @$content->id))) }}
@else
{{ Form::model($content, array('method' => 'POST', 'files'=>true, 'class'=>'main-form', 'action' => array('ContentsController@anyUpdate', @$content->id))) }}
@endif
<div class="tab-content">
<?php 
$i = 0; 
if(!$contentSection){
    $settings['Content'] = 'dummy';
}
if(!$advanced){
    $settings['Advanced'] = 'dummy';
}

//we want to set the form array here since we sometimes disable stuff etc.
$fieldArray = array('class'=>'form-control');
if($permission->result === false){
    $fieldArray[] = 'disabled';
}

?>
@foreach($settings as $key=>$section)
    <?php
    //we need to group this correctly.. I think there is a bug in Laravel that prevents 
    //nested groups working correctly. TODO: Probably look at this again later after I've
    //had some sleep]
    $fields = "";
    if($key != 'Advanced'){
        $model = new Baum\Extensions\Eloquent\Collection;
        
        if(count($section) >= 1 && ($section != 'dummy')){
            foreach($section as $flds){
                $model->push($flds);
            }
            $fields = $model->groupBy('name');
        }
    }
    ?>
    <div class="tab-pane {{$i==0?'active in':''}} fade edit-content-tab" id="tab-{{$key}}">
        <ul>
            @if($key == 'Advanced')

                <li class="form-group">
                    {{ Form::label('slug', 'Slug:') }}
                    <div class="input-group">
                        <?php
                        $niceFullSlug = "http://".ApplicationUrl::getApplicationUrl()->domain;
                        $niceFullSlug .= ApplicationUrl::getApplicationUrl()->folder=='/'?'':ApplicationUrl::getApplicationUrl()->folder;
                        ?>
                        <span class="input-group-addon">{{$niceFullSlug}}</span>
                    {{ Form::text('slug', null, $fieldArray) }}
                    </div>
                </li>

                <li class="form-group">
                    {{ Form::label('identifier', 'Identifier:') }}
                    {{ Form::input('identifier', 'identifier', null, $fieldArray) }}
                </li>

                <li class="form-group">
                    {{ Form::label('parent_id', 'Parent_id:') }}
                    {{ Form::input('number', 'parent_id', null, $fieldArray) }}
                </li>
                
                <li class="form-group">
                    {{ Form::label('package', 'Package:') }}
                    {{ Form::input('text', 'package', null, $fieldArray) }}
                </li>
                <li class="form-group">
                    {{ Form::label('service_provider', 'Service Provider:') }}
                    {{ Form::input('text', 'service_provider', null, $fieldArray) }}
                </li>
                <li class="form-group">
                    {{ Form::label('view', 'View:') }}
                    {{ Form::input('text', 'view', null, $fieldArray) }}
                </li>
                <li class="form-group">
                    {{ Form::label('layout', 'Layout:') }}
                    {{ Form::input('text', 'layout', null, $fieldArray) }}
                </li>

                <li class="form-group">
                    {{ Form::label('content_type_id', 'content_type_id:') }}
                    {{ Form::input('number', 'content_type_id', null, $fieldArray) }}
                </li>
            @endif
            
            @if($i == 0)
                <li class="form-group">
                    {{ Form::label('name', 'Name:') }}
                    {{ Form::text('name', null, $fieldArray) }}
                </li>
                <li class="form-group">
                    <label>Status:</label>
                    <div class="radio">
                        <label>
                            @if($permission->result === false)
                            {{ Form::radio('status','0','',array('disabled')) }}
                            @else
                            {{ Form::radio('status','0','') }}
                            @endif
                            
                            Draft
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            @if($permission->result === false)
                            {{ Form::radio('status','1','',array('disabled')) }}
                            @else
                            {{ Form::radio('status','1','') }}
                            @endif
                            Published
                        </label>
                    </div>
                </li>
            @endif
            @if(@$fields)
                @foreach($fields as $field)
                {{-- This is where the custom input types are rendered in. --}}
                    <li class="form-group">
                        <?php
                        $view = @$field[0]->field_type?$field[0]->field_type:'text';
                        ?>
                        @include("cms::contents.input_types.$view", array('setting'=>$field, 'content'=>$content))
                    </li>
                @endforeach
            @endif


            <li class="form-group">
                <div class='btn-group btn-group-lg'>
                    @if($permission->result === false)
                    {{ Form::submit(@$content->id?'Update':'Create', array('class' => 'btn btn-success disabled')) }}
                    {{ link_to_action('ContentsController@anyEdit', 'Cancel', @$content->id, array('class' => 'btn btn-danger disabled')) }}
                    @else
                    {{ Form::submit(@$content->id?'Update':'Create', array('class' => 'btn btn-success ')) }}
                    {{ link_to_action('ContentsController@anyEdit', 'Cancel', @$content->id, array('class' => 'btn btn-danger ')) }}
                    @endif                    
                </div>
            </li>
        </ul>
    </div>
    <?php $i++; ?>
@endforeach
    <div class="tab-pane edit-content-tab fade" id="tab-Permission">
        @include($content->edit_package.'::contents.permission', array('content'=>@$content, 'permission'=>@$permission))
    </div>
</div>
{{ Form::close() }}