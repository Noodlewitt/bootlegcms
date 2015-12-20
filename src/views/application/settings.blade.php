@extends(view()->exists($cms_package.'::layouts.master') ? $cms_package.'::layouts.master' : 'cms::layouts.master')
@section('main-content')
    <div class="col-md-offset-2 col-md-10">
        <div class="page-header row">	
                <!-- Page header, center on small screens -->
                <h1 class="col-xs-12"><i class="glyphicon glyphicon-cog"></i>&nbsp;&nbsp;Application Settings</h1>
        </div>
        
        <div class="row">
            <div class="col-xs-12">
                <ul class="nav nav-tabs">
                    @foreach($application_settings as $key=>$section)
                        <li class='{{@!$i?"active":""}}'><a href="#tab-{{$key?$key:"Settings"}}" data-toggle="tab">{{$key?$key:"Settings"}}</a></li>
                        <?php $i=1?>
                    @endforeach
                </ul>
                {!! Form::model($application, array('method' => 'POST', 'files'=>true, 'class'=>'main-form',  'action' => array('\Bootleg\Cms\ApplicationController@anyUpdate'))) !!}
                <div class='tab-content'>
                @foreach($application_settings as $key=>$section)

                    <div class="tab-pane fade {{!@$j?'active in':''}} edit-content-tab" id="tab-{{$key?$key:"Settings"}}">
                        <ul>
                            @if(!@$j)
                                <li class="form-group">
                                    {!! Form::label('name', 'Application Name:') !!}
                                    {!! Form::text('name', null, array('class'=>'form-control')) !!}
                                </li>
                                <li class="form-group">
                                    {!! Form::label('domains', 'Application Domain(s):') !!}
                                    {{-- We have to do this manually --}}
                                    <select name='' class='form-control tag' multiple>
                                        @foreach($application->url as $url)
                                            <option value="{{$url->domain}}" selected>{{$url->domain}}</option>
                                        @endforeach
                                    </select>
                                </li>
                                <?php
                                    $j=true;
                                ?>
                            @endif


                            @if($section !== false)
                                @foreach($section as $field)
                                {{-- This is where the custom input types are rendered in. --}}
                                    <li class="form-group">
                                        @include("cms::contents.input_types.".$field->field_type, array('setting'=>$field, 'applicationItem'=>$application))
                                    </li>
                                @endforeach
                            @endif
                            <li class="form-group">
                                <div class='btn-group btn-group-lg'>
                                    {!! Form::submit('Update', array('class' => 'btn btn-success')) !!}
                                    {!! link_to_action('\Bootleg\Cms\ApplicationController@anySettings', 'Cancel', @$content->id, array('class' => 'btn btn-danger')) !!}
                                </div>
                            </li>
                        </ul>
                    </div>
                    @endforeach
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <script type="text/javascript">
    $(function () {
        $('.tag').select2({
            tags:true
        });
    });
    </script>
@stop
