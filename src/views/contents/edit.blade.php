    <div class='overlay'></div>
    <div class="page-header row">
        <!-- Page header, center on small screens -->
        <h1 class="col-xs-12"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;&nbsp;{{@$content->id?'Update':'Create'}} {{$content->name or 'Content'}}</h1>
    </div>
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
    @if($content_mode == 'template')
    {!! Form::model($content, array('method' => 'POST', 'files'=>true, 'class'=>'main-form', 'action' => array('\Bootleg\Cms\TemplateController@anyUpdate', @$content->id))) !!}
    @else
    {!! Form::model($content, array('method' => 'POST', 'files'=>true, 'class'=>'main-form', 'action' => array('\Bootleg\Cms\ContentsController@anyUpdate', @$content->id))) !!}
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
                        {!! Form::label('slug', 'Slug:') !!} <button class='btn btn-default btn-xs js-generate-slug'>generate</button>
                        <div class="input-group">

                            <?php
                            $niceFullSlug = "http://".ApplicationUrl::getApplicationUrl()->domain;
                            $niceFullSlug .= ApplicationUrl::getApplicationUrl()->folder=='/'?'':ApplicationUrl::getApplicationUrl()->folder;
                            ?>
                            <span class="input-group-addon">{{$niceFullSlug}}</span>
                        {!! Form::text('slug', null, array('class'=>'form-control')) !!}
                        </div>
                    </li>

                    <li class="form-group">
                        {!! Form::label('identifier', 'Identifier:') !!}
                        {!! Form::input('identifier', 'identifier', null, array('class'=>'form-control')) !!}
                    </li>

                    <li class="form-group">
                        {!! Form::label('parent_id', 'Parent_id:') !!}
                        {!! Form::input('number', 'parent_id', null, array('class'=>'form-control')) !!}
                    </li>
                    
                    <li class="form-group">
                        {!! Form::label('package', 'Package:') !!}
                        {!! Form::input('text', 'package', null, array('class'=>'form-control')) !!}
                    </li>
                    <li class="form-group">
                        {!! Form::label('service_provider', 'Service Provider:') !!}
                        {!! Form::input('text', 'service_provider', null, array('class'=>'form-control')) !!}
                    </li>
                    <li class="form-group">
                        {!! Form::label('view', 'View:') !!}
                        {!! Form::input('text', 'view', null, array('class'=>'form-control')) !!}
                    </li>
                    <li class="form-group">
                        {!! Form::label('layout', 'Layout:') !!}
                        {!! Form::input('text', 'layout', null, array('class'=>'form-control')) !!}
                    </li>
                    @if($content_mode == 'content')
                        <li class="form-group">
                            {!! Form::label('template_id', 'Template ID:') !!}
                            {!! Form::input('number', 'template_id', null, array('class'=>'form-control')) !!}
                        </li>
                    @endif
                @endif
                
                @if($i == 0)
                    <li class="form-group">
                        {!! Form::label('name', 'Name:') !!}
                        {!! Form::text('name', null, array('class'=>'form-control js-content-name')) !!}
                    </li>
                    <li class="form-group">
                        <label>Status:</label>
                        <div class="radio">
                            <label>
                                {!! Form::radio('status','0','') !!}
                                Draft
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                {!!Form::radio('status','1','') !!}
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
                        {!! Form::submit(@$content->id?'Update':'Create', array('class' => 'btn btn-success ')) !!}
                        {!! link_to_action('\Bootleg\Cms\ContentsController@anyEdit', 'Cancel', @$content->id, array('class' => 'btn btn-danger ')) !!}
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

    <script type="text/javascript">
    $(function () {
        $('.js-generate-slug').click(function(e){
            e.preventDefault();
            var str = $('.js-content-name').val().replace(/ /g, '-');
            str = '/'+str.replace(/[^a-zA-Z0-9-_]/g, '');
            $('.js-slug').val(str.toLowerCase());
        });
    });
    </script>
    {!! Form::close() !!}