<a href="{{ $crud->route('edit', [ $entry->id ]) }}"
   title="{{ $crud->trans('actions.edit') }}"
   data-type="action"
   data-action="show">

    <i class="material-icons">&#xE254;</i>
</a>

<a href="{{ $crud->route('delete', [ $entry-> id ]) }}"
   title="{{ $crud->trans('actions.delete') }}"
   data-type="action"
   data-action="delete"
   data-confirm="{{ $crud->trans('messages.delete-confirmation') }}">

    <i class="material-icons">&#xE92B;</i>
</a>