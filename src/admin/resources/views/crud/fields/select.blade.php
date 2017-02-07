<div class="input-{{ $field->options->get('size', '100') }}">
    <label for="{{ $field->id }}">{{ $field->label }}</label>
    {!! Form::select($field->name, $options, old($field->name, $value), $field->attributes) !!}
</div>