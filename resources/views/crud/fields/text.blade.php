<div class="field">
    <label for="{{ $field->id }}">{{ $field->label }}</label>
    {!! Form::text($field->name, old($field->name), $field->attributes) !!}
</div>