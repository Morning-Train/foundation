<div class="input-{{ $field->options->get('size', '100') }}">
    <label for="{{ $field->id }}">{{ $field->label }}</label>
    @if($field->options->get('type', 'text') === 'password')
        {!! Form::password($field->name, $field->attributes) !!}
    @else
        {!! call_user_func(
            [ Form::class, $field->options->get('type', 'text') ],
            $field->name, old($field->name, $value), $field->attributes
        ) !!}
    @endif
</div>