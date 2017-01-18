@extends($crud->view('page'))

@section('content-inner')
    <div class="controls" >
        <div class="button">
            <a href="{{ $crud->route('create') }}">
                <i class="material-icons">&#xE145;</i>
                <span>{{ $crud->title('create') }}</span>
            </a>
        </div>
    </div>
    <div class="tight" >
        {{ Form::open([ 'route' => $crud->routeName() ]) }}
        <table class="table-small">
            <thead>
                <tr>
                    @if (!$crud->columns->isEmpty())
                        @foreach($crud->columns as $column)
                            <th class="{{ $column->class }}" data-sortable="{{ $column->sortable ? '1' : '0' }}" data-order="{{ $column->order or 'none' }}">
                                {{ $column->label }}
                                <i class="material-icons order-asc">&#xE5C7;</i>
                                <i class="material-icons order-desc">&#xE5C5;</i>
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