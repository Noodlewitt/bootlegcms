@foreach($setting as $field)
    <label>{{ ucfirst("$field->value") }}</label>
@endforeach
