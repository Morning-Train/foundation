@extends(Theme::view('auth'))
@section('title', trans('admin.auth.routes.login.title'))
@section('content')
    <div id="login" class="form-container">
        <h1>@yield('title')</h1>
        {!! Form::open([ 'route' => 'auth.login.do' ]) !!}

        <div class="input-100">
            {!! Form::email('email', old('email'), [
                    'placeholder'   => trans('admin.auth.fields.email'),
                    'required'      => true
            ]) !!}
        </div>

        <div class="input-100">
            {!! Form::password('password', [
                    'placeholder'   => trans('admin.auth.fields.password'),
                    'required'      => true
            ]) !!}
        </div>

        <div class="submission">
            {!! Form::submit(trans('admin.auth.routes.login.submit')) !!}
        </div>

        {!! Form::close() !!}

        <div class="actions">
            <a href="{{ route('auth.forgot-password') }}">{{ trans('admin.auth.actions.forgot-password') }}</a>

            @if(config('admin.auth.registration', true) === true)
                <a href="{{ route('auth.register') }}">{{ trans('admin.auth.actions.register') }}</a>
            @endif
        </div>
    </div>
@stop