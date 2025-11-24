@extends('layouts.app')

@section('title', 'Shifts')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="bi bi-clock"></i> Shifts</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('shifts.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Shift
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Shift Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Grace Period</th>
                        <th>Working Days</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shifts as $shift)
                    <tr>
                        <td><strong>{{ $shift->name }}</strong></td>
                        <td>{{ $shift->start_time }}</td>
                        <td>{{ $shift->end_time }}</td>
                        <td>{{ $shift->grace_period_minutes }} min</td>
                        <td><small>{{ is_array($shift->working_days) ? implode(', ', $shift->working_days) : $shift->working_days }}</small></td>
                        <td>
                            <a href="{{ route('shifts.edit', $shift) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No shifts found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
