<nav class="profile-nav sidebar {{ Theme::getMenuStatus('profile_sidebar', 'closed') === 'closed' ? 'closed' : '' }}" data-slug="profile_sidebar" data-align="right" >
    <div class="sidebar-toggle" data-toggle="profile_sidebar">
        <i class="close material-icons">&#xE315;&#xE315;</i>
    </div>
    <ul>
        <li><a href="#">Profile</a></li>
        <li><a href="#">Settings</a></li>
        <li><a href="{{route('auth.logout')}}">Log out</a></li>
    </ul>
</nav>

<div class="header {{ Theme::getMenuStatus('profile_sidebar', 'closed') === 'closed' ? 'sidebar-closed' : '' }}">
    <div class="header-content-wrapper">
        <div class="logo">
            <span class="v-center"></span>
            <a href="{{ route('admin') }}" >
                <img src="{{asset('img/logo.png')}}" srcset="{{asset('img/logo@2x.png')}} 2x, {{asset('img/logo@3x.png')}} 3x" />
            </a>
        </div>

        <div class="nav-toggle"  data-toggle="profile_sidebar">
            @if(Auth::check() && Auth::user()->hasAvatar())
                <img class="avatar" src="{{ Auth::user()->avatar }}" />
            @else
                <i class="material-icons">&#xE853;</i>
            @endif
        </div>
    </div>
</div>