{{ Form::label("setting[".$setting[0]->name."][".$setting[0]->id."]", ucfirst($setting[0]->name.":")) }}
<?php
    $options = json_decode(Templatesetting::DEFAULT_TABLE_JSON);
  
?>
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
        <tr>
            @foreach($options->values as $key=>$option)
            <th>{{$key}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <tr>
            @foreach($options->values as $key=>$option)
            <td>v</td>
            @endforeach
        </tr>
    </tbody>
  </table>
</div>
@foreach($setting as $field)
    

<div class='input-group'>
    <span class="input-group-addon"><i class='fa fa-facebook'></i></span>
    {{ Form::text("setting[".$field->name."][".$field->id."]", $field->value, array('class'=>'form-control')) }}
</div>
@endforeach