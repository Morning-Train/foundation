{!! $namespace !!}

{!! $imports !!}
@yield('imports')

class {!! $class !!}{!! $extends !!}{!! $implements !!} {
    {!! $uses !!}
    @yield('uses')

    @yield('body')
}