{!! Form::label("setting[".$setting[0]->name."][".$setting[0]->id."]", ucfirst($setting[0]->name.":")) !!}
@foreach($setting as $field)
<h2>TODO: FINISH THIS </h2>
<div class='input-group'>
    <span class="input-group-addon"><i class='fa fa-facebook'></i></span>
    {!! Form::text("setting[".$field->name."][".$field->id."]", $field->value, array('class'=>'form-control')) !!}
</div>
@endforeach