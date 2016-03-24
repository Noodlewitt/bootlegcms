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
$niceName = uniqid().preg_replace('/\s+/', '', $setting->name);
$options = array('class'=>'form-control');
if(@$params->tooltip->text){
    $options['data-toggle'] = "tooltip";
    $options['data-placement'] = @$params->tooltip->postion?$params->tooltip->postion:"left";
    $options['title'] = $params->tooltip->text;

}

?>
<div class="{{$niceName}}-container">
{!! Form::label("setting[".$setting->name."][".$setting->id."]", ucfirst($setting->name.":")) !!}
<div class="panel-group" id="{{$niceName}}" role="tablist" aria-multiselectable="true">
    <?php
    
    //we need to grab all this item's children (from template)
    if($content_mode == 'contents'){
        if(get_class($setting) == 'Templatesetting'){
            $template_children = \Templatesetting::where('parent_id',$setting->id)->get();
        }
        else{
          //dd($setting->templatesetting_id);
            if($setting->templatesetting_id){
                $template_children = \Templatesetting::where('parent_id',$setting->templatesetting_id)->get();
//              dd($template_children);
            }
        }
    }
    else{

        $template_children = \Templatesetting::where('parent_id',$setting->id)->get();
    }
//        dd($setting->id);
    //dd($template_children->lists('id'));

    $settingGroups = \Contentsetting::whereIn('templatesetting_id',$template_children->lists('id'))->where('content_id',$content->id)->get();
      //  dd($settingGroups);

//dd($content_mode, $setting->id, $settingGroups, $template_children->lists('id'));
        
    //dd($setting->multichildren()->get()->groupBy('index'));
    //$contentSettings = \Contentsetting::where('id',$setting->templatesetting_id)->get();
    //dd($setting);
        
 //  $settingGroups = $setting->multichildren()->get()->groupBy('index');
 //
 //  if($content_mode == 'contents'){

 //      if(@$setting->templatesetting_id){
 //          $templateGroup = $setting->templatesetting()->first();
 //          $templateGroupItems = \Templatesetting::where('parent_id', $templateGroup->id)->get();
 //      }
 //      else{
 //          $templateGroupItems = \Templatesetting::where('parent_id',$setting->id)->get();
 //      }
 //  }
    $count = 0;
    ?>
    <input type='hidden' name="setting[{{$setting->name}}][_multi][{{get_class($setting)}}]" value="{{$setting->id}}" />
    @if($content_mode == 'contents' && get_class($settingGroups->first()[0]) != 'Templatesetting')
        @foreach($settingGroups as $key=>$settingGroup)
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading{{$key}}">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#{{$niceName}}-collapse{{$key}}" aria-expanded="{{$count?'false':'true'}}" aria-controls="collapse{{$key}}">
                            {{$setting->name}}
                            <button class='js-delete-multi btn btn-xs pull-right btn-danger'>delete</button>
                        </a>
                    </h4>
                </div>
                <div id="{{$niceName}}-collapse{{$key}}" class="panel-collapse collapse {{!$count?'in':''}}" role="tabpanel" aria-labelledby="collapse{{$key}}">
                    <div class="panel-body">
                        @include("cms::contents.input_types.".$settingGroup->field_type, array('setting'=>$settingGroup, 'contentItem'=>$content, 'name'=>"setting[".$settingGroup->name."][".get_class($settingGroup)."][".$settingGroup->id."][".@$settingGroup->templatesetting_id."]"))
                    </div>
                </div>
            </div>
            <?php
            $count++;
            ?>
        @endforeach
    @endif
    {{--temp for now --}}
</div>
@if($content_mode == 'contents')
<div class='duplicator hidden'>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse" aria-expanded="true" aria-controls="collapse">
                    {{$setting->name}}
                    <button class='js-delete-multi btn btn-xs pull-right btn-danger'>delete</button>
                </a>
            </h4>
        </div>
        <div id="collapse" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="collapse">
            <div class="panel-body">
                @foreach($template_children as $key=>$field)
                    @include("cms::contents.input_types.".$field->field_type, array('setting'=>$field, 'contentItem'=>$content, 'name'=>false, 'opts' =>['data-name'=> "setting[".$setting->name."][".get_class($field)."][".$setting->id."][".$field->id."]"]))
                @endforeach
            </div>
        </div>
    </div>
</div>
<button class='js-add-multi btn btn-primary'>add</button>
</div>
@endif
<script type="text/javascript">
    $(function () {
        $('.{{$niceName}}-container button.js-add-multi').click(function(e){
            var multi_count = $('.panel-group .panel',$(this).parent()).length;
            e.preventDefault();

            $parent = $(this).closest('.form-group');
            $duplicated = $('.duplicator .panel', $parent).clone();
            var $heading = $('.panel-heading', $duplicated).attr('id','heading'+multi_count);
            $('a', $heading).attr('href','#collapse'+multi_count).attr('aria-controls','#collapse'+multi_count);

            $('.panel-collapse', $duplicated).attr('id','collapse'+multi_count).attr('aria-labelledby','#collapse'+multi_count);

            $('.form-control', $duplicated).attr('name',$('.form-control', $duplicated).attr('data-name')+"["+multi_count+"]");

            $('.panel-group', $parent).append($duplicated);
        });

        $('body').on('click','.{{$niceName}}-container button.js-delete-multi', function(e){
            e.preventDefault();
            $panel = $(this).closest('.panel');

            $('input, textarea', $panel).val('').attr('name', $('input, textarea', $panel).attr('name')+'[deleted]');
            $panel.hide();
        });

    });
</script>
