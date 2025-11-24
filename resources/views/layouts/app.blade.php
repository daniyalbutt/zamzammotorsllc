<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Zamzam CRM') }} - @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-car-front-fill"></i> Zamzam CRM
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        @if(auth()->user()->hasAnyRole(['Super Admin', 'Sales Manager', 'Sales Agent']))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}" href="{{ route('vehicles.index') }}">
                                    <i class="bi bi-car-front"></i> Vehicles
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->hasAnyRole(['Super Admin', 'Sales Manager', 'Sales Agent']))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                                    <i class="bi bi-people"></i> Customers
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->hasAnyRole(['Super Admin', 'Sales Manager', 'Sales Agent']))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">
                                    <i class="bi bi-receipt"></i> Invoices
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->hasAnyRole(['Super Admin', 'HR']))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->is('employees*') || request()->is('departments*') || request()->is('shifts*') || request()->is('attendance*') || request()->is('leaves*') || request()->is('payrolls*') ? 'active' : '' }}"
                                   href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-briefcase"></i> HR
                                </a>
                                <ul class="dropdown-menu">
                                    @can('view employees')
                                        <li><a class="dropdown-item" href="{{ route('employees.index') }}">Employees</a></li>
                                    @endcan
                                    @can('view departments')
                                        <li><a class="dropdown-item" href="{{ route('departments.index') }}">Departments</a></li>
                                    @endcan
                                    @can('view shifts')
                                        <li><a class="dropdown-item" href="{{ route('shifts.index') }}">Shifts</a></li>
                                    @endcan
                                    @can('view attendance')
                                        <li><a class="dropdown-item" href="{{ route('attendance.index') }}">Attendance</a></li>
                                    @endcan
                                    @can('view leaves')
                                        <li><a class="dropdown-item" href="{{ route('leaves.index') }}">Leave Requests</a></li>
                                    @endcan
                                    @can('view payroll')
                                        <li><a class="dropdown-item" href="{{ route('payrolls.index') }}">Payroll</a></li>
                                    @endcan
                                    @can('view announcements')
                                        <li><a class="dropdown-item" href="{{ route('announcements.index') }}">Announcements</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endif

                        @if(auth()->user()->hasAnyRole(['Super Admin', 'Sales Manager']))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                                   href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-graph-up"></i> Reports
                                </a>
                                <ul class="dropdown-menu">
                                    @can('view sales reports')
                                        <li><a class="dropdown-item" href="{{ route('reports.sales') }}">Sales Reports</a></li>
                                    @endcan
                                    @can('view hr reports')
                                        <li><a class="dropdown-item" href="{{ route('reports.hr') }}">HR Reports</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endif
                    @endauth
                </ul>

                <!-- Right Side -->
                <ul class="navbar-nav ms-auto">
                    @auth
                        @if(auth()->user()->employee)
                            <li class="nav-item">
                                <form method="POST" action="{{ route('attendance.mark') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-calendar-check"></i> Mark Attendance
                                    </button>
                                </form>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person"></i> Profile
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container-fluid">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-light text-center text-muted py-3 mt-5">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Zamzam CRM. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
