@extends('layouts.html')

@section('body')
    @include('admin.partials.notifications')
    @include('admin.partials.header')

    <div class="page {{ Theme::getMenuStatus('profile_sidebar', 'closed') === 'closed' ? 'sidebar-closed' : '' }}">
        @include('admin.partials.sidebar')
        <div class="content {{ Theme::getMenuStatus('main_sidebar', 'open') === 'closed' ? 'sidebar-closed' : '' }}">
            <div class="container" >
                @yield('content')
            </div>
        </div>
    </div>
@stop