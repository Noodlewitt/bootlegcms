<h1>{{@$content->id?'Update':'Create'}} {{$content->name or 'Content'}}</h1>
@include('cms::layouts.flash_messages')
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

{{ Form::model($content, array('method' => 'POST', 'files'=>true, 'class'=>'main-form', 'action' => array('\Bootleg\Cms\ContentsController@anyUpdate', @$content->id))) }}
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

    $fieldArray[] = 'disabled';


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
                            {{ Form::radio('status','0','') }}
                            Draft
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            {{ Form::radio('status','1','') }}
                            Published
                        </label>
                    </div>
                </li>
            @endif
            @if(@$fields)
                <li class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                        <tr>
                            @foreach($fields as $key=>$field)
                                <th>{{$key}}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                       /* for ($i=count($fields->first()); $i < ; $i++) { 
                            $field = $fields->offsetGet($i);
                        }*/
                        ?>
                        <tr>
                            @foreach($fields as $key=>$field)
                            <td>{{var_dump($field->value)}}</td>
                            @endforeach
                        </tr>
                    </tbody>
                  </table>
                </li>
            @endif


            <li class="form-group">
                <div class='btn-group btn-group-lg'>
                    {{ Form::submit(@$content->id?'Update':'Create', array('class' => 'btn btn-success ')) }}
                    {{ link_to_action('\Bootleg\Cms\ContentsController@anyEdit', 'Cancel', @$content->id, array('class' => 'btn btn-danger ')) }}
                </div>
            </li>
        </ul>
    </div>
    <?php $i++; ?>
@endforeach
    <div class="tab-pane edit-content-tab fade" id="tab-Permission">
        @include($content->edit_package.'::contents.permission', array('content'=>@$content))
    </div>
</div>
{{ Form::close() }}