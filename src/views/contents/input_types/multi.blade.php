<?php
/*
This is used to house a list of multiple elements
 */

if(@$content){

    $settingAfterEvent = \Event::fire('content.text.draw', array('content'=>$content, 'setting'=>$setting));
    $settingAfterEvent = reset($settingAfterEvent);
    if(!empty($settingAfterEvent)){
        $setting = $settingAfterEvent;
    }
}
$params = \Contentsetting::parseParams($setting);
$niceName = preg_replace('/\s+/', '', $setting->name);
$options = array('class'=>'form-control');
if(@$params->tooltip->text){
    $options['data-toggle'] = "tooltip";
    $options['data-placement'] = @$params->tooltip->postion?$params->tooltip->postion:"left";
    $options['title'] = $params->tooltip->text;
}
?>
{!! Form::label("setting[".$setting->name."][".$setting->id."]", ucfirst($setting->name.":")) !!}
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <?php
    //we need to grab all the settings
    //dd($setting->multichildren()->get()->groupBy('index'));
    $settingGroups = $setting->multichildren()->get()->groupBy('index');
    $count = 0;

    ?>
    @foreach($settingGroups as $key=>$settingGroup)
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading{{$key}}">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key}}" aria-expanded="{{$count?'false':'true'}}" aria-controls="collapse{{$key}}">
                        {{$setting->name}}
                        <button class='js-delete-multi btn pull-right btn-danger'>delete</button>
                    </a>
                </h4>
            </div>
            <div id="collapse{{$key}}" class="panel-collapse collapse {{!$count?'in':''}}" role="tabpanel" aria-labelledby="collapse{{$key}}">
                <div class="panel-body">
                    @foreach($settingGroup as $field)
                        @include("cms::contents.input_types.".$field->field_type, array('setting'=>$field, 'contentItem'=>$content))
                    @endforeach
                </div>
            </div>
        </div>
        <?php
        $count++;
        ?>
    @endforeach
    {{--temp for now --}}
    @if(isset($settingGroup)) 
    <div class='duplicator'>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading{{$key}}">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key}}" aria-expanded="{{$count?'false':'true'}}" aria-controls="collapse{{$key}}">
                        {{$setting->name}}
                        <button class='js-delete-multi btn pull-right btn-danger'>delete</button>
                    </a>
                </h4>
            </div>
            <div id="collapse{{$key}}" class="panel-collapse collapse {{!$count?'in':''}}" role="tabpanel" aria-labelledby="collapse{{$key}}">
                <div class="panel-body">
                    @foreach($settingGroup as $field)
                        @include("cms::contents.input_types.".$field->field_type, array('setting'=>$field, 'contentItem'=>$content))
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<button class='js-add-multi'>add</button>

<script type="text/javascript">
    $(function () {
        $('button.js-add-multi').click(function(e){
            e.preventDefault();
            $duplicated = $('.duplicator .panel').clone();
            $('.panel-group').append($duplicated);
        });

        $('button.js-delete-multi').click(function(e){
            e.preventDefault();
            $panel = $(this).closest('.panel');
            $('input, textarea', $panel).val('');
            $panel.hide();
        });
        
    });
</script>
