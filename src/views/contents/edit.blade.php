
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

            @if(count($application->languages) > 1)
                <li class='js-language-select'>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Languages:{{\App::getLocale()}} <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                        @foreach($application->languages as $language)

                        <li><a href="{{Applicationurl::getBaseUrl().config('bootlegcms.cms_route')}}{{$language->code}}{{'/'. $content_mode .'/'.'edit'.'/'.$content->id}}">{{$language->name}}</a></li>

                        @endforeach
                        <li role="separator" class="divider"></li>
                            <li><a href="#">Set Current Language As My Default</a></li>
                        </ul>
                    </div>
                </li>
            @endif
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
                        {!! Form::label('package', 'Package:') !!}
                        {!! Form::input('text', 'package', null, array('class'=>'form-control')) !!}
                    </li>
                    <li class="form-group">
                        {!! Form::label('view', 'View:') !!}
                        {!! Form::input('text', 'view', null, array('class'=>'form-control')) !!}
                    </li>
                    <li class="form-group">
                        {!! Form::label('headers', 'Headers:') !!}
                        {!! Form::input('text', 'headers', null, array('class'=>'form-control')) !!}
                    </li>
                    @if($content_mode == 'contents')
                        <li class="form-group">
                            {!! Form::label('template_id', 'Template ID:') !!}
                            {!! Form::input('number', 'template_id', null, array('class'=>'form-control')) !!}
                        </li>
                    @else
                        <li class="form-group">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('loopback','1',$content->loopback) !!}
                                    Loopback
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('auto_create','1',$content->auto_create) !!}
                                    Auto Create
                                </label>
                            </div>
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
                        {!! Form::submit(@$content->id?trans('cms::messages.button.update'):trans('cms::messages.button.create'), array('class' => 'btn btn-success ')) !!}
                        {!! link_to_action('\Bootleg\Cms\ContentsController@anyEdit', trans('cms::messages.button.cancel'), @$content->id, array('class' => 'btn btn-danger ')) !!}
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

        @if(count($application->languages) > 1)
        $('.js-language-select a').click(function(e){
            e.preventDefault();
            $.get(($(this).attr('href')), function(data){
                $('.main-content').html(data);
            });
            
        });
        @endif
    });
    </script>
    {!! Form::close() !!}