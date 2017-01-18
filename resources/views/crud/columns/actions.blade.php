<a href="{{ $crud->route('edit', [ $entry->id ]) }}">{{ $crud->trans('actions.edit') }}</a>
<a href="{{ $crud->route('delete', [ $entry-> id ]) }}">{{ $crud->trans('actions.delete') }}</a>