<div class="input-{{ $field->options->get('size', '100') }}">
    <label for="{{ $field->id }}">{{ $field->label }}</label>
    {!! Form::text($field->name, old($field->name), $field->attributes) !!}
</div>