@extends(Theme::current()->view('page'))
@section('title', $crud->title())

@section('content')
    <h1>{!! $crud->title() !!}</h1>
    @section('content-inner')
    @stop
@stop