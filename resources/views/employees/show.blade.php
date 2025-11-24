@extends('layouts.app')

@section('title', 'Employee Details')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-person-badge"></i> Employee Profile</h4>
                    @can('edit employees')
                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">Personal Information</h5>
                            <p class="mb-2">
                                <strong>Full Name:</strong><br>
                                <span class="fs-5">{{ $employee->user->name }}</span>
                            </p>
                            <p class="mb-2">
                                <strong>Email:</strong><br>
                                <a href="mailto:{{ $employee->user->email }}">{{ $employee->user->email }}</a>
                            </p>
                            @if($employee->user->phone)
                                <p class="mb-2">
                                    <strong>Phone:</strong><br>
                                    {{ $employee->user->phone }}
                                </p>
                            @endif
                            <p class="mb-2">
                                <strong>Employee ID:</strong><br>
                                <span class="badge bg-primary">{{ $employee->employee_id }}</span>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">Employment Details</h5>
                            <p class="mb-2">
                                <strong>Designation:</strong><br>
                                {{ $employee->designation }}
                            </p>
                            <p class="mb-2">
                                <strong>Department:</strong><br>
                                <span class="badge bg-success">{{ $employee->department->name ?? 'N/A' }}</span>
                            </p>
                            <p class="mb-2">
                                <strong>Shift:</strong><br>
                                {{ $employee->shift->name ?? 'N/A' }}
                                @if($employee->shift)
                                    <small class="text-muted">
                                        ({{ $employee->shift->start_time }} - {{ $employee->shift->end_time }})
                                    </small>
                                @endif
                            </p>
                            <p class="mb-2">
                                <strong>Joining Date:</strong><br>
                                {{ $employee->joining_date->format('M d, Y') }}
                            </p>
                            <p class="mb-2">
                                <strong>Monthly Salary:</strong><br>
                                <span class="fs-5 text-primary fw-bold">${{ number_format($employee->salary, 2) }}</span>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <!-- Statistics Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="text-muted mb-3">Employment Statistics</h5>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="bi bi-calendar-check text-success" style="font-size: 2rem;"></i>
                                    <h3 class="mb-0 mt-2">{{ $employee->attendances()->count() }}</h3>
                                    <p class="text-muted mb-0">Total Attendance</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="bi bi-calendar-x text-warning" style="font-size: 2rem;"></i>
                                    <h3 class="mb-0 mt-2">{{ $employee->leaves()->count() }}</h3>
                                    <p class="text-muted mb-0">Leave Requests</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                    <h3 class="mb-0 mt-2">{{ $employee->leaves()->where('status', 'Approved')->count() }}</h3>
                                    <p class="text-muted mb-0">Approved Leaves</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="bi bi-cash-stack text-primary" style="font-size: 2rem;"></i>
                                    <h3 class="mb-0 mt-2">{{ $employee->payrolls()->count() }}</h3>
                                    <p class="text-muted mb-0">Payroll Records</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Recent Attendance -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Recent Attendance (Last 5)</h6>
                            @if($employee->attendances()->count() > 0)
                                <div class="list-group">
                                    @foreach($employee->attendances()->latest()->limit(5)->get() as $attendance)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $attendance->date->format('M d, Y') }}</strong><br>
                                                <small class="text-muted">{{ $attendance->check_in_time }}</small>
                                            </div>
                                            <span class="badge {{ $attendance->status === 'Present' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $attendance->status }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No attendance records found.</p>
                            @endif
                        </div>

                        <!-- Recent Payrolls -->
                        <div class="col-md-6">
                            <h6 class="text-muted">Recent Payrolls (Last 5)</h6>
                            @if($employee->payrolls()->count() > 0)
                                <div class="list-group">
                                    @foreach($employee->payrolls()->latest()->limit(5)->get() as $payroll)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ \Carbon\Carbon::parse($payroll->month_year)->format('F Y') }}</strong><br>
                                                <small class="text-muted">${{ number_format($payroll->net_salary, 2) }}</small>
                                            </div>
                                            <span class="badge {{ $payroll->status === 'Paid' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $payroll->status }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No payroll records found.</p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>

                        <div>
                            @can('edit employees')
                                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            @endcan

                            @can('delete employees')
                                <form action="{{ route('employees.destroy', $employee) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this employee? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
