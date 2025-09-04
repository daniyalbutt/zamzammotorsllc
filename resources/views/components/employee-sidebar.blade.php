@canany(['view attendance'])
    <li class="slide has-sub {{ request()->routeIs('attendance.show') ? 'active' : '' }}">
        <a href="javascript:void(0);" class="sidebar__menu-item {{ request()->routeIs('attendance.*') &&  !request()->routeIs('attendance.myLeaves') ? 'active' : '' }}">
            <div class="side-menu__icon"><i class="fa-sharp fa-regular fa-right-left"></i></div>
            <span class="sidebar__menu-label">Attendance</span>
            <i class="fa-regular fa-angle-down side-menu__angle"></i>
        </a>
        <ul class="sidebar-menu child1 {{ request()->routeIs('attendance.show') ? 'active' : '' }}">
            @can('view attendance')
                <li class="slide {{ Request::routeIs('attendance.show') ? 'active' : '' }}">
                    <a href="{{ route('attendance.show') }}"
                        class="sidebar__menu-item {{ Request::routeIs('attendance.show') ? 'active' : '' }}">View Attendance</a>
                </li>
            @endcan

        </ul>
    </li>
@endcanany
@can('apply leave')
    <li class="slide">
        <a href="{{ route('attendance.myLeaves') }}"
            class="sidebar__menu-item {{ request()->routeIs('attendance.myLeaves') ? 'active' : '' }}">
            <div class="side-menu__icon"><i class="icon-plane"></i></div>
            <span class="sidebar__menu-label">Leaves</span>
        </a>
    </li>
@endcan
