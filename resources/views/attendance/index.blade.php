@extends('layouts.app')

@section('title', 'Attendance')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-calendar-check"></i> Today's Attendance</h2>
        <p class="text-muted">{{ now()->format('l, F d, Y') }}</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($attendances->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Shift</th>
                            <th>Check In Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                        <tr>
                            <td>
                                <i class="bi bi-person-circle"></i>
                                {{ $attendance->employee->user->name }}
                            </td>
                            <td>{{ $attendance->employee->department->name ?? 'N/A' }}</td>
                            <td>{{ $attendance->employee->shift->name ?? 'N/A' }}</td>
                            <td>{{ $attendance->check_in_time }}</td>
                            <td>
                                @if($attendance->status == 'Present')
                                    <span class="badge bg-success">Present</span>
                                @elseif($attendance->status == 'Late')
                                    <span class="badge bg-warning">Late</span>
                                @else
                                    <span class="badge bg-danger">Absent</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $attendances->links() }}
            </div>
        @else
            <div class="alert alert-info mb-0">
                <i class="bi bi-info-circle"></i> No attendance records for today yet.
            </div>
        @endif
    </div>
</div>
@endsection
