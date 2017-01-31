@extends($crud->view('page'))

@section('content-inner')
    <div class="form-container">
        {!! Form::model($entry, [ 'route' => [ $crud->routeName('store'), $entry->id ] ]) !!}
        @if(!$crud->fields->isEmpty())
            @foreach($crud->fields as $field)
                {!! $field->render($entry, $crud) !!}
            @endforeach
        @endif
        <input type="submit" value="{{ $entry->isNew() ? $crud->trans('buttons.create') : $crud->trans('buttons.update') }}"/>
        {!! Form::close() !!}
    </div>
@overwrite