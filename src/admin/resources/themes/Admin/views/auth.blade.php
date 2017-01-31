@extends('layouts.html')
@section('body')
    <div class="authentication">
        @include('auth.partials.notifications')
        <div class="authentication-content">
            @yield('content')
        </div>
    </div>
@stop