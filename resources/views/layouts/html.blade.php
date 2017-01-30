<!DOCTYPE html>
<html class="@yield('html_class')">
<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>

    <meta name="description" content="{{ Theme::get('description') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= csrf_token() ?>"/>

    {{-- Theme -> head action --}}
    {!! Theme::do('head') !!}

    @yield('head')
</head>
<body class="{{ Theme::get('bodyClass') }}">
@yield('body')
{!! Theme::do('footer') !!}
</body>
</html>