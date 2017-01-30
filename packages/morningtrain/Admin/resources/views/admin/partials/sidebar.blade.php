<div class="sidebar main-nav {{ Theme::getMenuStatus('main_sidebar', 'open') === 'closed' ? 'closed' : '' }}" data-slug="main_sidebar" data-align="left">
    <div class="sidebar-toggle" data-toggle="main_sidebar">
        <i class="open material-icons">&#xE315;&#xE315;</i>
        <i class="close material-icons">&#xE314;&#xE314;</i>
    </div>
    <nav>
        <ul>
            @foreach(Theme::getMainMenuItems() as $item)
                @can($item->slug . '.manage')
                <li>
                    <a class="{{ Request::is($item->basepath . '*') ? 'active' : '' }}"
                       href="{{ $item->path }}">
                        <span class="active-span"></span>
                        <i class="material-icons">{{ isset($item->params['icon']) ? $item->params['icon'] : '' }}</i>
                        <span>{{ $item->label }}</span>
                    </a>
                </li>
                @endcan
            @endforeach
        </ul>
    </nav>
</div>

