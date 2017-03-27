<div class="input-{{ $field->options->get('size', '100') }}">
    <label for="{{ $field->id }}">{{ $field->label }}</label>
    {!! Form::select($field->nameAttribute, $options, old($field->nameAttribute, $value), $field->attributes) !!}
</div>