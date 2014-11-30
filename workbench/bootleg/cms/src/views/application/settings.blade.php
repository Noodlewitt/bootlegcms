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
            {{ Form::model($application, array('method' => 'POST', 'files'=>true, 'class'=>'main-form',  'action' => array('ApplicationController@anyUpdate'))) }}
            <div class='tab-content'>
            @foreach($application_settings as $key=>$section)
            <?php
                //we need to group this correctly.. I think there is a bug in Laravel that prevents 
                //nested groups working correctly. TODO: Probably look at this again later after I've
                //had some sleep]
                $fields = "";
                $model = new Baum\Extensions\Eloquent\Collection;
                if(count($section) > 1){
                    foreach($section as $flds){
                        $model->push($flds);
                    }
                    $fields = $model->groupBy('name');
                }
                ?>
                <div class="tab-pane fade {{!@$j?'active in':''}} edit-content-tab" id="tab-{{$key?$key:"Settings"}}">
                    <ul>
                        @if(!@$j)
                            <li class="form-group">
                                {{ Form::label('name', 'Name:') }}
                                {{ Form::text('name', null, array('class'=>'form-control')) }}
                            </li>
                            <li class="form-group">
                                {{ Form::label('theme', 'Theme:') }}
                                {{ Form::select('theme', Theme::lists('name', 'id'), null, array('class'=>'form-control')) }}
                            </li>
                            <?php
                                $j=true;
                            ?>
                        @endif
                        @if(@$fields)
                            @foreach($fields as $field)
                                <li class="form-group">
                                    <?php
                                    $view = @$field[0]->field_type?$field[0]->field_type:'text';
                                    ?>
                                    @include("cms::contents.input_types.$view", array('setting'=>$field, 'application'=>$application))
                                </li>
                            @endforeach
                        @endif
                        <li class="form-group">
                            {{ Form::submit('Update', array('class' => 'btn btn-success')) }}
                            {{ link_to_action('ApplicationController@anySettings', 'Cancel', @$content->id, array('class' => 'btn')) }}
                        </li>
                    </ul>
                </div>
                @endforeach
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
