<?php

$setting_class = get_class($setting[0]);
$settingAfterEvent = \Event::fire('content.dropdown.draw', [$setting]);
$settingAfterEvent = reset($settingAfterEvent);
if(!empty($settingAfterEvent) && $settingAfterEvent[0] instanceof $setting_class) $setting = $settingAfterEvent;

$params = Contentsetting::parseParams($setting[0]);
$niceName = preg_replace('/\s+/', '', $setting[0]->name);
$field_title = isset($params->field_title) ? $params->field_title : $setting[0]->name;
$values = (array)$params->values;

?>
{!! Form::label("setting[".$setting[0]->name."][".$setting[0]->id."]", ucfirst($field_title.":")) !!}
<div class='text {{$niceName}}' >
    @foreach($setting as $field)
        @if(isset($params->max_number)  && $params->max_number > 1)
            {!! Form::select("setting[".$field->name."][".get_class($field)."][".$field->id."][]",  $values, $field->value, ['class'=>'form-control', 'multiple'=>'multiple', 'id'=>str_slug('setting_'.$field->name."_".get_class($field)."_".$field->id, '_')])!!}
        @else
            {!! Form::select("setting[".$field->name."][".get_class($field)."][".$field->id."]",  $values, $field->value, ['class'=>'form-control'])!!}
        @endif
    @endforeach
</div>

@if(isset($params->max_number)  && $params->max_number > 1)
    <script>
        $(document).ready(function() {
            $('#{{ str_slug('setting_'.$field->name."_".get_class($field)."_".$field->id, '_') }}').multiselect({
                maxHeight: 200,
                includeSelectAllOption: true,
                enableFiltering: true,
                onChange: function(option, checked) {
                    // Get selected options.
                    var selectedOptions = $('#{{ str_slug('setting_'.$field->name."_".get_class($field)."_".$field->id, '_') }} option:selected');

                    if (selectedOptions.length >= 4) {
                        // Disable all other checkboxes.
                        var nonSelectedOptions = $('#{{ str_slug('setting_'.$field->name."_".get_class($field)."_".$field->id, '_') }} option').filter(function() {
                            return !$(this).is(':selected');
                        });

                        var dropdown = $('#{{ str_slug('setting_'.$field->name."_".get_class($field)."_".$field->id, '_') }}').siblings('.multiselect-container');
                        nonSelectedOptions.each(function() {
                            var input = $('input[value="' + $(this).val() + '"]');
                            input.prop('disabled', true);
                            input.parent('li').addClass('disabled');
                        });
                    }
                    else {
                        // Enable all checkboxes.
                        var dropdown = $('#{{ str_slug('setting_'.$field->name."_".get_class($field)."_".$field->id, '_') }}').siblings('.multiselect-container');
                        $('#{{ str_slug('setting_'.$field->name."_".get_class($field)."_".$field->id, '_') }} option').each(function() {
                            var input = $('input[value="' + $(this).val() + '"]');
                            input.prop('disabled', false);
                            input.parent('li').addClass('disabled');
                        });
                    }
                }
            });
        });
    </script>
@endif
