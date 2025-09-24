<li class="slide">
    <a href="{{route('forums.index')}}" class="sidebar__menu-item {{ request()->routeIs('forums.*') ? 'active' : '' }}">
        <div class="side-menu__icon"><i class="icon-announcement"></i></div>
        <span class="sidebar__menu-label">Forums</span>
    </a>
</li>

<li class="slide">
    <a href="{{ route('customers.index') }}" class="sidebar__menu-item {{ request()->routeIs('customers.index') ? 'active' : '' }}">
        <div class="side-menu__icon"><i class="fa-sharp fa-light fa-user"></i></div>
        <span class="sidebar__menu-label">Clients</span>
    </a>
</li>
