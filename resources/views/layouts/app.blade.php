<!-- https://html.bdevs.net/manez.prev/ -->
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.webp') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @include('includes.css')
    @stack('css')
</head>

<body class="body-area">
    <!-- Preloader start -->
    <div class="preloader" id="preloader">
        <div class="loading">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <!-- Preloader start -->

    <div class="page__full-wrapper">
        <!-- App sidebar area start -->
        <!-- app-sidebar-start -->
        <div class="app-sidebar" id="sidebar">
            <div class="main-sidebar-header">
                <a href="{{ route('home') }}" class="header-logo">
                    <img class="main-logo" src="{{ asset('imgs/logo.png') }}" alt="logo">
                </a>
            </div>
            <div class="main-sidebar" id="sidebar-scroll">
                <nav class="main-menu-container nav nav-pills flex-column sub-open">
                    <div class="sidebar-left" id="sidebar-left"></div>
                    <ul class="main-menu">
                        <li class="slide">
                            <a href="{{ route('home') }}"
                                class="sidebar__menu-item {{ Request::routeIs('home') ? 'active' : '' }}">
                                <div class="side-menu__icon"><i class="icon-house"></i></div>
                                <span class="sidebar__menu-label">Dashboards</span>
                            </a>
                        </li>
                        @can('role')
                            <li class="slide has-sub {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                <a href="javascript:void(0);"
                                    class="sidebar__menu-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                    <div class="side-menu__icon"><i class="fa-sharp fa-light fa-key"></i></div>
                                    <span class="sidebar__menu-label">Roles</span>
                                    <i class="fa-regular fa-angle-down side-menu__angle"></i>
                                </a>
                                <ul class="sidebar-menu child1 {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                    @can('create role')
                                        <li class="slide {{ Request::routeIs('roles.create') ? 'active' : '' }}">
                                            <a href="{{ route('roles.create') }}"
                                                class="sidebar__menu-item {{ Request::routeIs('roles.create') ? 'active' : '' }}">Add
                                                Role</a>
                                        </li>
                                    @endcan
                                    <li class="slide {{ Request::routeIs('roles.index') ? 'active' : '' }}">
                                        <a href="{{ route('roles.index') }}"
                                            class="sidebar__menu-item {{ Request::routeIs('roles.index') ? 'active' : '' }}">Role
                                            List</a>    
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        @can('user')
                            <li class="slide has-sub {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <a href="javascript:void(0);"
                                    class="sidebar__menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                    <div class="side-menu__icon"><i class="fa-sharp fa-light fa-user"></i></div>
                                    <span class="sidebar__menu-label">Users</span>
                                    <i class="fa-regular fa-angle-down side-menu__angle"></i>
                                </a>
                                <ul class="sidebar-menu child1 {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                    @canany(['create user', 'create edit assigned customer','create edit assigned agent','create edit all customer','create edit all agent'])
                                        <li class="slide {{ Request::routeIs('users.create') ? 'active' : '' }}">
                                            <a href="{{ route('users.create') }}"
                                                class="sidebar__menu-item {{ Request::routeIs('users.create') ? 'active' : '' }}">Add
                                                User</a>
                                        </li>
                                    @endcanany
                                    <li class="slide {{ Request::routeIs('users.index') ? 'active' : '' }}">
                                        <a href="{{ route('users.index') }}"
                                            class="sidebar__menu-item {{ Request::routeIs('users.index') ? 'active' : '' }}">User
                                            List</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        <li class="sidebar__menu-category"><span class="category-name">Listing</span></li>


                        @can('make')
                            <li class="slide has-sub {{ request()->routeIs('makes.*') ? 'active' : '' }}">
                                <a href="javascript:void(0);"
                                    class="sidebar__menu-item {{ request()->routeIs('makes.*') ? 'active' : '' }}">
                                    <div class="side-menu__icon"><i class="fa-sharp fa-regular fa-right-left"></i></div>
                                    <span class="sidebar__menu-label">Make</span>
                                    <i class="fa-regular fa-angle-down side-menu__angle"></i>
                                </a>
                                <ul class="sidebar-menu child1 {{ request()->routeIs('makes.*') ? 'active' : '' }}">
                                    @can('create make')
                                        <li class="slide {{ Request::routeIs('makes.create') ? 'active' : '' }}">
                                            <a href="{{ route('makes.create') }}"
                                                class="sidebar__menu-item {{ Request::routeIs('makes.create') ? 'active' : '' }}">Add
                                                Make</a>
                                        </li>
                                    @endcan
                                    <li class="slide {{ Request::routeIs('makes.index') ? 'active' : '' }}">
                                        <a href="{{ route('makes.index') }}"
                                            class="sidebar__menu-item {{ Request::routeIs('makes.index') ? 'active' : '' }}">Make
                                            List</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        @can('model')
                            <li class="slide has-sub {{ request()->routeIs('models.*') ? 'active' : '' }}">
                                <a href="javascript:void(0);"
                                    class="sidebar__menu-item {{ request()->routeIs('models.*') ? 'active' : '' }}">
                                    <div class="side-menu__icon"><i class="fa-sharp fa-regular fa-right-left"></i></div>
                                    <span class="sidebar__menu-label">Model</span>
                                    <i class="fa-regular fa-angle-down side-menu__angle"></i>
                                </a>
                                <ul class="sidebar-menu child1 {{ request()->routeIs('models.*') ? 'active' : '' }}">
                                    @can('create make')
                                        <li class="slide {{ Request::routeIs('models.create') ? 'active' : '' }}">
                                            <a href="{{ route('models.create') }}"
                                                class="sidebar__menu-item {{ Request::routeIs('models.create') ? 'active' : '' }}">Add
                                                Model</a>
                                        </li>
                                    @endcan
                                    <li class="slide {{ Request::routeIs('models.index') ? 'active' : '' }}">
                                        <a href="{{ route('models.index') }}"
                                            class="sidebar__menu-item {{ Request::routeIs('models.index') ? 'active' : '' }}">Model
                                            List</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan




                        @can('body type')
                            <li class="slide has-sub {{ request()->routeIs('body-types.*') ? 'active' : '' }}">
                                <a href="javascript:void(0);"
                                    class="sidebar__menu-item {{ request()->routeIs('body-types.*') ? 'active' : '' }}">
                                    <div class="side-menu__icon"><i class="fa-sharp fa-regular fa-right-left"></i></div>
                                    <span class="sidebar__menu-label">Body Type</span>
                                    <i class="fa-regular fa-angle-down side-menu__angle"></i>
                                </a>
                                <ul class="sidebar-menu child1 {{ request()->routeIs('body-types.*') ? 'active' : '' }}">
                                    @can('create body type')
                                        <li class="slide {{ Request::routeIs('body-types.create') ? 'active' : '' }}">
                                            <a href="{{ route('body-types.create') }}"
                                                class="sidebar__menu-item {{ Request::routeIs('body-types.create') ? 'active' : '' }}">Add
                                                Body Type</a>
                                        </li>
                                    @endcan
                                    <li class="slide {{ Request::routeIs('body-types.index') ? 'active' : '' }}">
                                        <a href="{{ route('body-types.index') }}"
                                            class="sidebar__menu-item {{ Request::routeIs('body-types.index') ? 'active' : '' }}">Body
                                            Type List</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan


                         @can('vehicles')
                            <li class="slide has-sub {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                                <a href="javascript:void(0);"
                                    class="sidebar__menu-item {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                                    <div class="side-menu__icon"><i class="fa-sharp fa-regular fa-right-left"></i></div>
                                    <span class="sidebar__menu-label">Vehicles</span>
                                    <i class="fa-regular fa-angle-down side-menu__angle"></i>
                                </a>
                                <ul class="sidebar-menu child1 {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                                    @can('create vehicles')
                                        <li class="slide {{ Request::routeIs('vehicles.create') ? 'active' : '' }}">
                                            <a href="{{ route('vehicles.create') }}"
                                                class="sidebar__menu-item {{ Request::routeIs('vehicles.create') ? 'active' : '' }}">Add
                                                Vehicles</a>
                                        </li>
                                    @endcan
                                    <li class="slide {{ Request::routeIs('vehicles.index') ? 'active' : '' }}">
                                        <a href="{{ route('vehicles.index') }}"
                                            class="sidebar__menu-item {{ Request::routeIs('vehicles.index') ? 'active' : '' }}">Vehicles</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        @role('hr')
                            <x:hr-sidebar />
                        @endrole
                        @role('employee')
                            <x:employee-sidebar />
                        @endrole

                    </ul>
                    <div class="sidebar-right" id="sidebar-right"></div>
                </nav>
            </div>
        </div>
        <div class="app__offcanvas-overlay"></div>
        <!-- app-sidebar-end -->
        <!-- App sidebar area end -->
        <div class="page__body-wrapper">
            <!-- App header area start -->
            <div class="app__header__area">
                <div class="app__header-inner">
                    <div class="app__header-left">
                        <div class="">
                            <a id="sidebar__active" class="app__header-toggle" href="javascript:void(0)">
                                <div class="bar-icon-2">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                        </div>
                        <h2 class="header__title">Hello {{ Auth::user()->name }} <span><img
                                    src="{{ asset('img/hand.png') }}" alt="image"></span></h2>
                    </div>
                    <div class="app__header-right">
                        <div class="app__header-action">
                            <ul>
                                <li>
                                    <a href="#!" onclick="javascript:toggleFullScreen()">
                                        <div class="nav-item">
                                            <div class="notification__icon">
                                                <svg width="22" height="22" viewBox="0 0 22 22"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M7.47106 21.549C7.09156 21.549 6.78356 21.2417 6.78356 20.8615V14.7984H0.6875C0.308 14.7984 0 14.4911 0 14.1109C0 13.7308 0.308 13.4234 0.6875 13.4234H7.47106C7.85056 13.4234 8.15856 13.7308 8.15856 14.1109V20.8615C8.15856 21.2417 7.85056 21.549 7.47106 21.549V21.549ZM14.5289 21.5318C14.1494 21.5318 13.8414 21.2245 13.8414 20.8443V14.0601C13.8414 13.6799 14.1494 13.3726 14.5289 13.3726H21.2795C21.659 13.3726 21.967 13.6799 21.967 14.0601C21.967 14.4403 21.659 14.7476 21.2795 14.7476H15.2164V20.8443C15.2164 21.2245 14.9084 21.5318 14.5289 21.5318V21.5318ZM7.47106 8.17644H0.7205C0.341 8.17644 0.033 7.86912 0.033 7.48894C0.033 7.10875 0.341 6.80144 0.7205 6.80144H6.78356V0.704688C6.78356 0.3245 7.09156 0.0171875 7.47106 0.0171875C7.85056 0.0171875 8.15856 0.3245 8.15856 0.704688V7.48894C8.15856 7.86844 7.85056 8.17644 7.47106 8.17644ZM21.3125 8.12556H14.5289C14.1494 8.12556 13.8414 7.81825 13.8414 7.43806V0.6875C13.8414 0.307312 14.1494 0 14.5289 0C14.9084 0 15.2164 0.307312 15.2164 0.6875V6.75056H21.3125C21.692 6.75056 22 7.05788 22 7.43806C22 7.81825 21.692 8.12556 21.3125 8.12556Z"
                                                        fill="#7A7A7A" />
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <div class="nav-item p-relative">
                                        <a id="notifydropdown" href="#">
                                            <div class="notification__icon">
                                                <svg width="22" height="22" viewBox="0 0 22 22"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_209_757)">
                                                        <path
                                                            d="M9.1665 22C7.27185 22 5.729 20.4582 5.729 18.5625C5.729 18.183 6.037 17.875 6.4165 17.875C6.79601 17.875 7.104 18.183 7.104 18.5625C7.104 19.7002 8.02985 20.625 9.1665 20.625C10.3032 20.625 11.229 19.7002 11.229 18.5625C11.229 18.183 11.537 17.875 11.9165 17.875C12.296 17.875 12.604 18.183 12.604 18.5625C12.604 20.4582 11.0613 22 9.1665 22Z"
                                                            fill="#7A7A7A" />
                                                        <path
                                                            d="M16.7291 19.2499H1.60411C0.719559 19.2499 0 18.5304 0 17.6458C0 17.1764 0.204437 16.7319 0.560944 16.4266C0.583939 16.4065 0.608612 16.3882 0.634293 16.3715C1.97992 15.1973 2.75 13.5079 2.75 11.724V9.16655C2.75 6.18106 4.77306 3.61805 7.66975 2.93323C8.04002 2.84797 8.41046 3.07439 8.49757 3.44483C8.58452 3.81426 8.35541 4.18453 7.98698 4.27164C5.71266 4.80875 4.125 6.82174 4.125 9.16655V11.724C4.125 13.9388 3.15417 16.0343 1.46396 17.4724C1.4502 17.4835 1.43828 17.4936 1.42351 17.5037C1.39883 17.5349 1.375 17.5826 1.375 17.6458C1.375 17.7704 1.47957 17.8749 1.60411 17.8749H16.7291C16.8538 17.8749 16.9584 17.7704 16.9584 17.6458C16.9584 17.5815 16.9346 17.5349 16.9089 17.5037C16.8951 17.4936 16.8822 17.4835 16.8694 17.4724C16.0482 16.7722 15.3999 15.9271 14.9436 14.9599C14.7804 14.617 14.9269 14.2073 15.2707 14.0442C15.6173 13.881 16.0233 14.0296 16.1856 14.3723C16.5485 15.1387 17.0573 15.8116 17.7008 16.3744C17.7246 16.3908 17.7495 16.4083 17.7704 16.4266C18.129 16.7319 18.3334 17.1764 18.3334 17.6458C18.3334 18.5304 17.6138 19.2499 16.7291 19.2499Z"
                                                            fill="#7A7A7A" />
                                                        <path
                                                            d="M16.0417 11.9166C12.7565 11.9166 10.0835 9.24365 10.0835 5.95839C10.0835 2.67296 12.7565 0 16.0417 0C19.3271 0 22.0001 2.67296 22.0001 5.95839C22.0001 9.24365 19.3271 11.9166 16.0417 11.9166ZM16.0417 1.375C13.5145 1.375 11.4585 3.43112 11.4585 5.95839C11.4585 8.48566 13.5145 10.5416 16.0417 10.5416C18.569 10.5416 20.6251 8.48566 20.6251 5.95839C20.6251 3.43112 18.569 1.375 16.0417 1.375Z"
                                                            fill="#7A7A7A" />
                                                        <path
                                                            d="M16.2709 8.70828C15.8914 8.70828 15.5834 8.40028 15.5834 8.02078V5.0415H15.125C14.7455 5.0415 14.4375 4.73351 14.4375 4.354C14.4375 3.9745 14.7455 3.6665 15.125 3.6665H16.2709C16.6504 3.6665 16.9584 3.9745 16.9584 4.354V8.02078C16.9584 8.40028 16.6504 8.70828 16.2709 8.70828Z"
                                                            fill="#7A7A7A" />
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_209_757">
                                                            <rect width="22" height="22" fill="white" />
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            </div>
                                        </a>
                                        <div class="notification__dropdown item-two">
                                            <div class="notification__card card__scroll">
                                                <div class="notification__header">
                                                    <div class="notification__inner">
                                                        <h5>Notifications</h5>
                                                        <span>(8)</span>
                                                    </div>
                                                </div>
                                               
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="nav-item p-relative">
                            <a id="userportfolio" href="#">
                                <div class="user__portfolio">
                                    <div class="user__portfolio-thumb">
                                        <img src="{{ Auth::user()->profileImage() }}" alt="img not found">
                                    </div>
                                    <div class="user__content">
                                        <h5>{{ Auth::user()->name }}</h5>
                                        <span>online</span>
                                    </div>
                                </div>
                        </a>
                            <div class="user__dropdown">
                                <ul>
                                    <li>
                                        <a href="">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_643_344)">
                                                    <path
                                                        d="M13.6569 10.3431C12.7855 9.47181 11.7484 8.82678 10.6168 8.43631C11.8288 7.60159 12.625 6.20463 12.625 4.625C12.625 2.07478 10.5502 0 8 0C5.44978 0 3.375 2.07478 3.375 4.625C3.375 6.20463 4.17122 7.60159 5.38319 8.43631C4.25162 8.82678 3.2145 9.47181 2.34316 10.3431C0.832156 11.8542 0 13.8631 0 16H1.25C1.25 12.278 4.27803 9.25 8 9.25C11.722 9.25 14.75 12.278 14.75 16H16C16 13.8631 15.1678 11.8542 13.6569 10.3431ZM8 8C6.13903 8 4.625 6.486 4.625 4.625C4.625 2.764 6.13903 1.25 8 1.25C9.86097 1.25 11.375 2.764 11.375 4.625C11.375 6.486 9.86097 8 8 8Z"
                                                        fill="#7A7A7A" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_643_344">
                                                        <rect width="16" height="16" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                            Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_643_343)">
                                                    <path
                                                        d="M17.4368 8.43771H10.312C10.0015 8.43771 9.74951 8.18572 9.74951 7.87523C9.74951 7.56474 10.0015 7.31274 10.312 7.31274H17.4368C17.7473 7.31274 17.9993 7.56474 17.9993 7.87523C17.9993 8.18572 17.7473 8.43771 17.4368 8.43771Z"
                                                        fill="#7A7A7A" />
                                                    <path
                                                        d="M14.6244 11.2502C14.4803 11.2502 14.3364 11.1954 14.2268 11.0852C14.0071 10.8654 14.0071 10.5091 14.2268 10.2894L16.6418 7.87457L14.2268 5.45958C14.0071 5.23986 14.0071 4.88364 14.2268 4.66392C14.4467 4.44406 14.8029 4.44406 15.0226 4.66392L17.835 7.47633C18.0547 7.69605 18.0547 8.05227 17.835 8.27199L15.0226 11.0844C14.9123 11.1954 14.7684 11.2502 14.6244 11.2502Z"
                                                        fill="#7A7A7A" />
                                                    <path
                                                        d="M5.99986 18.0002C5.83932 18.0002 5.68703 17.9776 5.53488 17.9304L1.02142 16.4267C0.407305 16.2122 0 15.64 0 15.0003V1.50073C0 0.673487 0.672754 0.000732422 1.5 0.000732422C1.66039 0.000732422 1.81269 0.0232537 1.96498 0.0704934L6.4783 1.5742C7.09255 1.7887 7.49972 2.36093 7.49972 3.00059V16.5002C7.49972 17.3274 6.8271 18.0002 5.99986 18.0002ZM1.5 1.1257C1.29374 1.1257 1.12496 1.29447 1.12496 1.50073V15.0003C1.12496 15.16 1.23222 15.3085 1.3852 15.3617L5.8775 16.8587C5.90977 16.8692 5.95179 16.8752 5.99986 16.8752C6.20612 16.8752 6.37475 16.7064 6.37475 16.5002V3.00059C6.37475 2.84088 6.2675 2.69244 6.11452 2.63915L1.62222 1.14218C1.58995 1.13174 1.54793 1.1257 1.5 1.1257Z"
                                                        fill="#7A7A7A" />
                                                    <path
                                                        d="M11.4371 6.00035C11.1266 6.00035 10.8746 5.74836 10.8746 5.43786V2.06297C10.8746 1.54622 10.454 1.12545 9.93722 1.12545H1.49998C1.18949 1.12545 0.9375 0.873462 0.9375 0.562971C0.9375 0.252479 1.18949 0.000488281 1.49998 0.000488281H9.93722C11.075 0.000488281 11.9996 0.925234 11.9996 2.06297V5.43786C11.9996 5.74836 11.7476 6.00035 11.4371 6.00035Z"
                                                        fill="#7A7A7A" />
                                                    <path
                                                        d="M9.93699 15.7501H6.93699C6.6265 15.7501 6.37451 15.4981 6.37451 15.1876C6.37451 14.8771 6.6265 14.6251 6.93699 14.6251H9.93699C10.4537 14.6251 10.8744 14.2044 10.8744 13.6876V10.3127C10.8744 10.0022 11.1264 9.75024 11.4369 9.75024C11.7473 9.75024 11.9993 10.0022 11.9993 10.3127V13.6876C11.9993 14.8254 11.0747 15.7501 9.93699 15.7501Z"
                                                        fill="#7A7A7A" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_643_343">
                                                        <rect width="18" height="18" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                            Log Out
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="body__overlay"></div>
            <!-- App header area end -->
            <!-- App side area start -->
            <div class="app__slide-wrapper">
                @yield('content')
            </div>
            <footer class="footer">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card__footer d-flex justify-content-center">
                            <p>Copyright © <span id="year"></span> <span class="text-black">Manez.</span>
                                Designed with by <a href="https://themeforest.net/user/bdevs"
                                    target="_blank">Bdevs</a> All rights reserved
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- footer area end -->
        </div>
    </div>
    <!-- Dashboard area end -->
    <!-- Back to top start -->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>

    <script src="{{ asset('js/jquery-3.7.0.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/waypoints.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/simplebar.min.js') }}"></script>
    <script src="{{ asset('js/simplebar-active.js') }}"></script>
    <script src="{{ asset('js/loader.js') }}"></script>
    <script src="{{ asset('js/smooth-scrollbar.js') }}"></script>
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    @stack('js')

    @include('includes.modals')
</body>

</html>
