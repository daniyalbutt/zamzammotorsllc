@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Attendance</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ date('F', mktime(0, 0, 0, $month, 10)) }} {{ $year }}</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">Attendance Overview</h1>
                    <p class="text-muted mb-0">Track your daily attendance and working hours for {{ date('F Y', mktime(0, 0, 0, $month, 10)) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Days</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ cal_days_in_month(CAL_GREGORIAN, $month, $year) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Hours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalhours / 3600, 1) }}h</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Present Days</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ collect($attendance)->where('status', 'present')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Absent Days</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ collect($attendance)->where('status', 'absent')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Attendance</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="monthSelect" class="form-label">Month</label>
                    <select class="form-select month-select" id="monthSelect">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="yearSelect" class="form-label">Year</label>
                    <select class="form-select year-select" id="yearSelect">
                        @for ($i = 2023; $i <= 2026; $i++)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <button class="btn btn-primary filter-btn me-2">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                    <button class="btn btn-success csv-btn">
                        <i class="fas fa-download me-1"></i> Export CSV
                    </button>
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <div class="card bg-light border-0 w-100">
                        <div class="card-body text-center py-2">
                            <small class="text-muted d-block">Total Working Hours</small>
                            <h5 class="mb-0 text-primary">{{ number_format($totalhours / 3600, 1) }}h</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daily Attendance</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="attendanceTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Working Hours</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach ($attendance as $day)
                            @php
                            dump($day);
                                $joiningDate = strtotime($user->getMeta('date_of_joining'));
                                $isBeforeJoining = $day['date'] < $joiningDate;
                            @endphp
                            <tr class="@if($isBeforeJoining) table-light @elseif($day['status'] == 'present') table-success @elseif($day['status'] == 'absent') table-danger @elseif($day['status'] == 'halfday') table-warning @elseif($day['status'] == 'weekend') table-info @elseif($day['status'] == 'today') table-secondary @endif">
                                <td>
                                    <strong>{{ date('d M Y', $day['date']) }}</strong>
                                </td>
                                <td>{{ $day['day'] }}</td>
                                <td>
                                    @if ($isBeforeJoining)
                                        <span class="text-muted">---</span>
                                    @elseif ($day['timein'] == '-' || $day['timein'] == null)
                                        <span class="text-muted">---</span>
                                    @else
                                        <span class="badge bg-secondary">{{ date('h:i A', $day['timein']) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($isBeforeJoining)
                                        <span class="text-muted">---</span>
                                    @elseif ($day['timeout'] == '-' || $day['timeout'] == null)
                                        <span class="text-muted">---</span>
                                    @else
                                        <span class="badge bg-secondary">{{ date('h:i A', $day['timeout']) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($isBeforeJoining)
                                        <span class="text-muted">---</span>
                                    @elseif ($day['totalhours'] == '-' || $day['totalhours'] == null)
                                        <span class="text-muted">---</span>
                                    @else
                                        <span class="badge bg-info">{{ gmdate('H:i', $day['totalhours']) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($isBeforeJoining)
                                        <span class="badge bg-light text-white">Before Join</span>
                                    @else
                                        @php
                                            $statusClasses = [
                                                'present' => 'bg-success',
                                                'absent' => 'bg-danger',
                                                'halfday' => 'bg-warning',
                                                'late' => 'bg-warning',
                                                'early' => 'bg-info',
                                                'today' => 'bg-secondary',
                                                'weekend' => 'bg-info',
                                                'future' => 'bg-light text-dark',
                                                'active' => 'bg-primary',
                                                'forgettotimeout' => 'bg-warning',
                                                'nohalfday' => 'bg-danger'
                                            ];
                                            $statusClass = $statusClasses[$day['status']] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ ucfirst($day['name']) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-success">Working Days Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-8">Present Days</div>
                        <div class="col-4 text-end">
                            <span class="badge bg-success">{{ collect($attendance)->where('status', 'present')->count() }}</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">Half Days</div>
                        <div class="col-4 text-end">
                            <span class="badge bg-warning">{{ collect($attendance)->where('status', 'halfday')->count() }}</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">Absent Days</div>
                        <div class="col-4 text-end">
                            <span class="badge bg-danger">{{ collect($attendance)->where('status', 'absent')->count() }}</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">Weekends</div>
                        <div class="col-4 text-end">
                            <span class="badge bg-info">{{ collect($attendance)->where('status', 'weekend')->count() }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">Before Join</div>
                        <div class="col-4 text-end">
                            @php
                                $joiningDate = strtotime($user->getMeta('date_of_joining'));
                                $beforeJoinCount = collect($attendance)->filter(function($day) use ($joiningDate) {
                                    return $day['date'] < $joiningDate;
                                })->count();
                            @endphp
                            <span class="badge bg-light text-dark">{{ $beforeJoinCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Time Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-8">Total Hours</div>
                        <div class="col-4 text-end">
                            <span class="badge bg-primary">{{ number_format($totalhours / 3600, 1) }}h</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">Average/Day</div>
                        <div class="col-4 text-end">
                            @php
                                $joiningDate = strtotime($user->getMeta('date_of_joining'));
                                $workingDays = collect($attendance)->filter(function($day) use ($joiningDate) {
                                    return $day['date'] >= $joiningDate && in_array($day['status'], ['present', 'halfday']);
                                })->count();
                                $avgHours = $workingDays > 0 ? ($totalhours / 3600) / $workingDays : 0;
                            @endphp
                            <span class="badge bg-primary">{{ number_format($avgHours, 1) }}h</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">Working Days</div>
                        <div class="col-4 text-end">
                            <span class="badge bg-success">{{ $workingDays }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-left-info shadow h-100">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-info">Month Overview</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-8">Month</div>
                        <div class="col-4 text-end">
                            <span class="badge bg-info">{{ date('F', mktime(0, 0, 0, $month, 10)) }}</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">Year</div>
                        <div class="col-4 text-end">
                            <span class="badge bg-info">{{ $year }}</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">Total Days</div>
                        <div class="col-4 text-end">
                            <span class="badge bg-info">{{ cal_days_in_month(CAL_GREGORIAN, $month, $year) }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">Attendance Rate</div>
                        <div class="col-4 text-end">
                            @php
                                $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                                $joiningDate = strtotime($user->getMeta('date_of_joining'));
                                $presentDays = collect($attendance)->filter(function($day) use ($joiningDate) {
                                    return $day['date'] >= $joiningDate && in_array($day['status'], ['present', 'halfday']);
                                })->count();
                                $rate = $totalDays > 0 ? ($presentDays / $totalDays) * 100 : 0;
                            @endphp
                            <span class="badge bg-success">{{ number_format($rate, 1) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Filter attendance
    $('.filter-btn').on('click', function() {
        var month = $('.month-select').val();
        var year = $('.year-select').val();
        var url = "{{ route('attendance.show', ['month' => ':month', 'year' => ':year']) }}";
        url = url.replace(':month', month);
        url = url.replace(':year', year);
        window.location.href = url;
    });

    // Export CSV
    $('.csv-btn').on('click', function() {
        var month = $('.month-select').val();
        var year = $('.year-select').val();
        // Add your CSV export logic here
        alert('CSV export functionality to be implemented');
    });

    // Initialize DataTable for better table experience
    if ($.fn.DataTable) {
        $('#attendanceTable').DataTable({
            "pageLength": 31,
            "order": [[0, "desc"]],
            "responsive": true,
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries per page",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.075);
}

.badge {
    font-size: 0.75em;
}

.table-responsive {
    border-radius: 0.35rem;
}

.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom: none;
}

.card-header h6 {
    color: white !important;
    font-weight: 600 !important;
}
</style>
@endpush
