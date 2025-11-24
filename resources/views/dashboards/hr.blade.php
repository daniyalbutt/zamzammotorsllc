@extends('layouts.app')

@section('title', 'HR Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-briefcase"></i> HR Dashboard</h2>
        <p class="text-muted">Manage employees, attendance, and payroll</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Total Employees</h6>
                        <h3 class="card-title mb-0">{{ $stats['total_employees'] }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-person-badge-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Present Today</h6>
                        <h3 class="card-title mb-0">{{ $stats['present_today'] }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-check-circle-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Pending Leaves</h6>
                        <h3 class="card-title mb-0">{{ $stats['pending_leaves'] }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-calendar-x-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Departments</h6>
                        <h3 class="card-title mb-0">{{ $stats['total_departments'] }}</h3>
                    </div>
                    <div class="text-info">
                        <i class="bi bi-building" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Recent Attendance</h5>
            </div>
            <div class="card-body">
                @if(isset($recent_attendance) && $recent_attendance->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Check In</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_attendance as $attendance)
                                <tr>
                                    <td>{{ $attendance->employee->user->name }}</td>
                                    <td>{{ $attendance->check_in_time }}</td>
                                    <td>
                                        @if($attendance->status == 'Present')
                                            <span class="badge bg-success">Present</span>
                                        @else
                                            <span class="badge bg-warning">Late</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No attendance records today.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-megaphone"></i> Recent Announcements</h5>
            </div>
            <div class="card-body">
                @if(isset($announcements) && $announcements->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($announcements as $announcement)
                        <div class="list-group-item">
                            <h6 class="mb-1">{{ $announcement->title }}</h6>
                            <small class="text-muted">{{ $announcement->created_at->diffForHumans() }}</small>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No announcements yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-lightning-fill"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-3">
                        <a href="{{ route('employees.create') }}" class="btn btn-primary w-100">
                            <i class="bi bi-person-plus"></i> Add Employee
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('attendance.index') }}" class="btn btn-success w-100">
                            <i class="bi bi-calendar-check"></i> Mark Attendance
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('leaves.index') }}" class="btn btn-warning w-100">
                            <i class="bi bi-calendar-x"></i> Leave Requests
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('announcements.create') }}" class="btn btn-info w-100">
                            <i class="bi bi-megaphone"></i> New Announcement
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
