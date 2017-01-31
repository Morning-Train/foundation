<div class="input-{{ $field->options->get('size', '100') }}">
    <label for="{{ $field->id }}">{{ $field->label }}</label>
    {!! call_user_func(
            [ Form::class, $field->options->get('type', 'text') ],
            $field->name, old($field->name), $field->attributes
    ) !!}
</div>