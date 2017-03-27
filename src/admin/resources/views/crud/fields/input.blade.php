<div class="input-{{ $field->options->get('size', '100') }}">
    <label for="{{ $field->id }}">{{ $field->label }}</label>
    @if($field->options->get('type', 'text') === 'password')
        {!! Form::password($field->nameAttribute, $field->attributes) !!}
    @else
        {!! call_user_func(
            [ Form::class, $field->options->get('type', 'text') ],
            $field->nameAttribute, old($field->nameAttribute, $value), $field->attributes
        ) !!}
    @endif
</div>