@extends(Theme::view('auth'))
@section('title', trans('admin.auth.routes.register.title'))
@section('content')
    <div id="register" class="form-container">
        <h1>@yield('title')</h1>
        {!! Form::open([ 'route' => 'auth.register.do' ]) !!}

        <div class="input-100">
            {!! Form::text('name', old('name'), [
                    'placeholder'   => trans('admin.auth.fields.name'),
                    'required'      => true
            ]) !!}
        </div>

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
            {!! Form::submit(trans('admin.auth.routes.register.submit')) !!}
        </div>

        {!! Form::close() !!}

        <div class="actions">
            <a href="{{ route('auth.login') }}">{{ trans('admin.auth.actions.back-to-login') }}</a>
        </div>
    </div>
@stop