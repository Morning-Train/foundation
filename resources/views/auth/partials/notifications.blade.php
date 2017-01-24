<div class="notifications">
    @if(count($errors))
        <div class="notification error">{{ $errors->first() }}</div>
    @endif

    @if(Session::has('status'))
        <div class="notification success">{{ Session::pull('status') }}</div>
    @endif
</div>