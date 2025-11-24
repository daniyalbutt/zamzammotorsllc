@extends('layouts.app')

@section('title', 'Create Shift')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-clock"></i> Create New Shift</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('shifts.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Shift Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="e.g., Morning Shift, Evening Shift"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_time" class="form-label">
                                    Start Time <span class="text-danger">*</span>
                                </label>
                                <input type="time"
                                       name="start_time"
                                       id="start_time"
                                       class="form-control @error('start_time') is-invalid @enderror"
                                       value="{{ old('start_time') }}"
                                       required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_time" class="form-label">
                                    End Time <span class="text-danger">*</span>
                                </label>
                                <input type="time"
                                       name="end_time"
                                       id="end_time"
                                       class="form-control @error('end_time') is-invalid @enderror"
                                       value="{{ old('end_time') }}"
                                       required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="grace_period_minutes" class="form-label">
                                Grace Period (Minutes) <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   name="grace_period_minutes"
                                   id="grace_period_minutes"
                                   class="form-control @error('grace_period_minutes') is-invalid @enderror"
                                   value="{{ old('grace_period_minutes', 15) }}"
                                   min="0"
                                   required>
                            @error('grace_period_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Minutes allowed after shift start time before marking as late
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Working Days <span class="text-danger">*</span>
                            </label>
                            <div class="border rounded p-3">
                                @php
                                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                    $oldWorkingDays = old('working_days', []);
                                @endphp
                                <div class="row">
                                    @foreach($days as $day)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       name="working_days[]"
                                                       value="{{ $day }}"
                                                       id="day_{{ $day }}"
                                                       {{ in_array($day, $oldWorkingDays) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="day_{{ $day }}">
                                                    {{ $day }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @error('working_days')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Select the days this shift is active
                            </small>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Note:</strong> The grace period helps determine if an employee is marked as "Late" when they check in after the shift start time.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('shifts.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Create Shift
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
