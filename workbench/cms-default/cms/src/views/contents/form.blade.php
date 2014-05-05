@include('cms::layouts.flash_messages')

<h1>{{@$content->id?'Update':'Create'}} {{$content->name or 'Content'}}</h1>

{{ Form::model($content, array('method' => 'POST', 'files'=>true, 'class'=>'main-form', 'action' => array('ContentsController@anyUpdate', @$content->id))) }}


<ul>
        <li class="form-group">
            {{ Form::label('name', 'Name:') }}
            {{ Form::text('name', null, array('class'=>'form-control')) }}
        </li>

        <li class="form-group">
            {{ Form::label('slug', 'Slug:') }}
            {{ Form::text('slug', null, array('class'=>'form-control')) }}
        </li class="form-group">

        <li class="form-group">
            <label>Status:</label>
            <div class="radio">
                <label>
                    {{ Form::radio('status','0','',array('class'=>'')) }}
                    Draft
                </label>
            </div>
            <div class="radio">
                <label>
                    {{ Form::radio('status','1','',array('class'=>'')) }}
                    Published
                </label>
            </div>
        </li>
        
        <li class="form-group">
            {{ Form::label('identifier', 'Identifier:') }}
            {{ Form::input('identifier', 'identifier', null, array('class'=>'form-control')) }}
        </li>
        
        <li class="form-group">
            {{ Form::label('parent_id', 'Parent_id:') }}
            {{ Form::input('number', 'parent_id', null, array('class'=>'form-control')) }}
        </li>
        
        <li class="form-group">
            {{ Form::label('content_type_id', 'content_type_id:') }}
            {{ Form::input('number', 'content_type_id', null, array('class'=>'form-control')) }}
        </li>
        
        @if(!empty($content_settings))
            @foreach($content_settings as $setting)
                <li class="form-group">
                    @include('cms::contents.input_types.'.$setting->field_type, array('setting'=>$setting, 'content_settings'=>$content_settings))
                </li>
            @endforeach
        @endif

        <li class="form-group">
            {{ Form::submit(@$content->id?'Update':'Create', array('class' => 'btn btn-success')) }}
            {{ link_to_action('ContentsController@anyEdit', 'Cancel', @$content->id, array('class' => 'btn')) }}
        </li>
    </ul>
{{ Form::close() }}

