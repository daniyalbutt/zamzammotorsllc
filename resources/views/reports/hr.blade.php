@extends('layouts.app')

@section('title', 'HR Reports')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-people-fill"></i> HR Reports</h2>
    </div>
</div>

<!-- Date Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.hr') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control" name="start_date" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control" name="end_date" value="{{ $endDate }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Generate Report
                    </button>
                </div>
            </div>
        </form>
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
                        <h6 class="card-subtitle mb-2 text-muted">Total Attendance</h6>
                        <h3 class="card-title mb-0">{{ $stats['total_attendance'] }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-calendar-check-fill" style="font-size: 2rem;"></i>
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
                        <h6 class="card-subtitle mb-2 text-muted">Present</h6>
                        <h3 class="card-title mb-0">{{ $stats['present_count'] }}</h3>
                    </div>
                    <div class="text-info">
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
                        <h6 class="card-subtitle mb-2 text-muted">Late</h6>
                        <h3 class="card-title mb-0">{{ $stats['late_count'] }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-clock-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Leaves -->
<div class="card">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-calendar-x"></i> Leave Summary</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Pending Leave Requests</h6>
                <h3 class="text-warning">{{ $stats['pending_leaves'] }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection
