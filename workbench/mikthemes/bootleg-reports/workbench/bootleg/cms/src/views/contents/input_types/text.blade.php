<?php
$params = Contentsetting::parseParams($setting[0]);
$niceName = preg_replace('/\s+/', '', $setting[0]->name);
?>
{{ Form::label("setting[".$setting[0]->name."][".$setting[0]->id."]", ucfirst($setting[0]->name.":")) }}

@if($params->count == 1)
    <div class='text {{$niceName}}' >   
        @foreach($setting as $field)
        {{ Form::text("setting[".$field->name."][".get_class($field)."][".$field->id."]", $field->value, array('class'=>'form-control')) }}
        @endforeach
    </div>
@else
    
    <div class='hidden template{{$niceName}}'>
        <div class="input-group new">
                <input class="form-control" data-name="setting[{{$setting[0]->name}}][{{get_class($field)}}][]" type="text">
                <span class="input-group-btn">
                    <button class="btn btn-default delete{{$niceName}}" type="button"><span class="glyphicon glyphicon-remove"></span></button>
                </span>
        </div>
    </div>
    
    <div class='text{{$niceName}}' >   
        <?php
        $count = count($setting);
        ?>
        @foreach($setting as $key=>$field)
            <div class="input-group">
                {{ Form::text("setting[".$field->name."][".get_class($field)."][".$field->id."]", $field->value, array('class'=>'form-control')) }}
                <span class="input-group-btn">
                    <button class="btn btn-default delete{{$niceName}}" type="button"><span class="glyphicon glyphicon-remove"></span></button>
                </span>
            </div>
        @endforeach
    </div>
    <button class='btn btn-default add{{$niceName}}'>Add</button> 

    <script>
        $(function() {
            var text_count_limit = {{$params->count}};
            
            $('button.add{{$niceName}}').click(function(e){
                e.preventDefault();
                if($('div.input-group', '.text{{$niceName}}').not('.deleted').length >= text_count_limit){
                    //modal add alert.
                }
                var $div = $($('.template{{$niceName}}').html());
                $input = $('input',$div);
                $input.attr('name', $input.attr('data-name'));
                $('div.text{{$niceName}}').append($div);
            });
            
            $('div.text{{$niceName}}').on('click', 'button.delete{{$niceName}}', function(e){
                e.preventDefault();
                $group = $(this).closest('div.input-group');
                $group.fadeOut(function(){
                    if($(this).hasClass('new')){
                        $(this).remove();
                    }
                });
                $group.addClass('deleted');
                $input = $('input', $group);
                var nm = $input.attr('name');
                $input.attr('name', nm+'[deleted]');
            });
        });
    </script>
@endif