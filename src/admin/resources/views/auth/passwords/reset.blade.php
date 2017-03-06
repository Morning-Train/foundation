@extends(Theme::view('auth'))
@section('title', trans('admin.auth.routes.reset-password.title'))
@section('content')
    <div id="reset-password" class="form-container">
        <h1>@yield('title')</h1>
        {!! Form::open([ 'route' => 'auth.reset-password.do' ]) !!}

        {!! Form::hidden('token', $token) !!}

        <div class="input-100">
            {!! Form::email('email', old('email'), [
                    'placeholder'   => trans('admin.auth.fields.email'),
                    'required'      => true
            ]) !!}
        </div>

        <div class="input-100">
            {!! Form::password('password', [
                    'placeholder'   => trans('admin.auth.fields.password'),
                    'required'      => true,
                    'autocomplete'  => 'off'
            ]) !!}
        </div>

        <div class="input-100">
            {!! Form::password('password_confirmation', [
                    'placeholder'   => trans('admin.auth.fields.password_confirmation'),
                    'required'      => true,
                    'autocomplete'  => 'off'
            ]) !!}
        </div>

        <div class="submission">
            {!! Form::submit(trans('admin.auth.routes.reset-password.submit')) !!}
        </div>

        {!! Form::close() !!}

        <div class="actions">
            <a href="{{ route('auth.login') }}">{{ trans('admin.auth.actions.back-to-login') }}</a>
        </div>
    </div>
@stop