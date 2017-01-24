@extends(Theme::view('auth'))
@section('title', trans('admin.auth.routes.forgot-password.title'))
@section('content')
    <div id="forgot-password" class="form-container">
        <h1>@yield('title')</h1>
        {!! Form::open([ 'route' => 'auth.forgot-password.do' ]) !!}

        <div class="input-100">
            {!! Form::email('email', old('email'), [
                    'placeholder'   => trans('admin.auth.fields.email'),
                    'required'      => true
            ]) !!}
        </div>

        <div class="submission">
            {!! Form::submit(trans('admin.auth.routes.forgot-password.submit')) !!}
        </div>

        {!! Form::close() !!}

        <div class="actions">
            <a href="{{ route('auth.login') }}">{{ trans('admin.auth.actions.back-to-login') }}</a>
        </div>
    </div>
@stop