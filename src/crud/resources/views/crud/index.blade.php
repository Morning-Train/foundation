@extends($crud->view('page'))

@section('content-inner')
    <div class="controls">
        <div class="button">
            <a href="{{ $crud->route('create') }}">
                <i class="material-icons">&#xE145;</i>
                <span>{{ $crud->trans('actions.create') }}</span>
            </a>
        </div>
    </div>
    <div class="tight">
        {{ Form::open([ 'route' => $crud->routeName(), 'class' => 'crud-index' ]) }}

        @if(!$crud->filters->isEmpty())
            <div class="index-filters">
                @foreach($crud->filters as $filter)
                    {!! $filter->render($crud) !!}
                @endforeach
                {!! Form::submit($crud->trans('buttons.filter')) !!}
            </div>
        @endif

        <table class="table-small">
            <thead>
            <tr>
                @if (!$crud->columns->isEmpty())
                    @foreach($crud->columns as $column)
                        <th class="{{ $column->class }}" data-name="{{ $column->name }}"
                            data-sortable="{{ $column->options->get('sortable', true) ? 'on' : 'off' }}"
                            data-order="{{ $column->order or 'none' }}">
                            {{ $column->label }}
                            <i class="material-icons order-icon">&#xE5C7;</i>
                        </th>
                    @endforeach
                @endif
            </tr>
            </thead>
            <tbody>
            @if(!$entries->isEmpty())
                @foreach($entries as $entry)
                    <tr>
                        @if(!$crud->columns->isEmpty())
                            @foreach($crud->columns as $column)
                                <td class="{{ $column->class }}">
                                    {!! $column->render($entry, $crud) !!}
                                </td>
                            @endforeach
                        @endif
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        {{ Form::close() }}
    </div>
    {!! $entries->render()!!}
@overwrite