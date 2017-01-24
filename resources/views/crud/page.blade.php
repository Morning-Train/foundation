@extends(Theme::view('page'))
@section('title', $crud->title())

@section('content')
    <h1>{!! $crud->title() !!}</h1>
    @yield('content-inner')
@stop