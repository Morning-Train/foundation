<div class="notifications">
    @if(count($errors))
        <div class="alert failure">
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    @if(Session::has('status'))
        <div class="alert success">
            <span>{{ Session::pull('status') }}</span>
        </div>
    @endif
</div>