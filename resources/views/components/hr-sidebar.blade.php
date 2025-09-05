@canany(['create employee', 'edit employee'])
    <li class="slide has-sub {{ request()->routeIs('employees.*') ? 'active' : '' }}">
        <a href="javascript:void(0);" class="sidebar__menu-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
            <div class="side-menu__icon"><i class="fa-sharp fa-regular fa-right-left"></i></div>
            <span class="sidebar__menu-label">Employees</span>
            <i class="fa-regular fa-angle-down side-menu__angle"></i>
        </a>
        <ul class="sidebar-menu child1 {{ request()->routeIs('employees.*') ? 'active' : '' }}">
            @can('create employee')
                <li class="slide {{ Request::routeIs('employees.create') ? 'active' : '' }}">
                    <a href="{{ route('employees.create') }}"
                        class="sidebar__menu-item {{ Request::routeIs('employees.create') ? 'active' : '' }}">Add
                        Employees</a>
                </li>
            @endcan
            @can('edit employee')
                <li class="slide {{ Request::routeIs('employees.index') ? 'active' : '' }}">
                    <a href="{{ route('employees.index') }}"
                        class="sidebar__menu-item {{ Request::routeIs('employees.index') ? 'active' : '' }}">Employees</a>
                </li>
            @endcan
        </ul>
    </li>
@endcanany
@canany(['create department', 'edit department'])
    <li class="slide has-sub {{ request()->routeIs('departments.*') ? 'active' : '' }}">
        <a href="javascript:void(0);" class="sidebar__menu-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
            <div class="side-menu__icon"><i class="fa-sharp fa-regular fa-right-left"></i></div>
            <span class="sidebar__menu-label">Departments</span>
            <i class="fa-regular fa-angle-down side-menu__angle"></i>
        </a>
        <ul class="sidebar-menu child1 {{ request()->routeIs('departments.*') ? 'active' : '' }}">
            @can('create department')
                <li class="slide {{ Request::routeIs('departments.create') ? 'active' : '' }}">
                    <a href="{{ route('departments.create') }}"
                        class="sidebar__menu-item {{ Request::routeIs('departments.create') ? 'active' : '' }}">Add
                        Departments</a>
                </li>
            @endcan
            @can('edit department')
                <li class="slide {{ Request::routeIs('departments.index') ? 'active' : '' }}">
                    <a href="{{ route('departments.index') }}"
                        class="sidebar__menu-item {{ Request::routeIs('departments.index') ? 'active' : '' }}">Departments</a>
                </li>
            @endcan
        </ul>
    </li>
@endcanany

@canany(['create shift', 'edit shift'])
    <li class="slide has-sub {{ request()->routeIs('shifts.*') ? 'active' : '' }}">
        <a href="javascript:void(0);" class="sidebar__menu-item {{ request()->routeIs('shifts.*') ? 'active' : '' }}">
            <div class="side-menu__icon"><i class="fa-sharp fa-regular fa-right-left"></i></div>
            <span class="sidebar__menu-label">Shifts</span>
            <i class="fa-regular fa-angle-down side-menu__angle"></i>
        </a>
        <ul class="sidebar-menu child1 {{ request()->routeIs('shifts.*') ? 'active' : '' }}">
            @can('create department')
                <li class="slide {{ Request::routeIs('shifts.create') ? 'active' : '' }}">
                    <a href="{{ route('shifts.create') }}"
                        class="sidebar__menu-item {{ Request::routeIs('shifts.create') ? 'active' : '' }}">Add
                        Shift</a>
                </li>
            @endcan
            @can('edit department')
                <li class="slide {{ Request::routeIs('shifts.index') ? 'active' : '' }}">
                    <a href="{{ route('shifts.index') }}"
                        class="sidebar__menu-item {{ Request::routeIs('shifts.index') ? 'active' : '' }}">Shift</a>
                </li>
            @endcan
        </ul>
    </li>
@endcanany

@can('view all live attendance')
    <li class="slide">
        <a href="{{ route('attendance.live') }}"
            class="sidebar__menu-item {{ request()->routeIs('attendance.live') ? 'active' : '' }}">
            <div class="side-menu__icon"><i class="icon-clock"></i></div>
            <span class="sidebar__menu-label">Company Attendance</span>
        </a>
    </li>
@endcan
@can('view company leaves')
    <li class="slide">
        <a href="{{ route('company.leaves') }}"
            class="sidebar__menu-item {{ request()->routeIs('company.leaves') ? 'active' : '' }}">
            <div class="side-menu__icon"><i class="icon-plane"></i></div>
            <span class="sidebar__menu-label">Company Leaves</span>
        </a>
    </li>

@endcan

<li class="slide">
    <a href="{{ route('payroll.index') }}"
        class="sidebar__menu-item {{ request()->routeIs('payroll') ? 'active' : '' }}">
        <div class="side-menu__icon"><i class="fa-sharp fa-light fa-wallet"></i></div>
        <span class="sidebar__menu-label">Payroll</span>
    </a>
</li>