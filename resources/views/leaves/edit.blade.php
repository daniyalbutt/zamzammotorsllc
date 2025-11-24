@extends('layouts.app')

@section('title', 'Edit Leave Request')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h4 class="mb-0"><i class="bi bi-pencil"></i> Edit Leave Request</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('leaves.update', $leave) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="leave_type" class="form-label">
                                Leave Type <span class="text-danger">*</span>
                            </label>
                            <select name="leave_type"
                                    id="leave_type"
                                    class="form-select @error('leave_type') is-invalid @enderror"
                                    required>
                                <option value="">Select leave type...</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type }}"
                                            {{ (old('leave_type', $leave->leave_type) === $type) ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                            @error('leave_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">
                                    Start Date <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       name="start_date"
                                       id="start_date"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date', $leave->start_date->format('Y-m-d')) }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">
                                    End Date <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       name="end_date"
                                       id="end_date"
                                       class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date', $leave->end_date->format('Y-m-d')) }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">
                                Reason <span class="text-danger">*</span>
                            </label>
                            <textarea name="reason"
                                      id="reason"
                                      class="form-control @error('reason') is-invalid @enderror"
                                      rows="4"
                                      maxlength="500"
                                      required
                                      placeholder="Please provide a detailed reason for your leave request...">{{ old('reason', $leave->reason) }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maximum 500 characters</small>
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Note:</strong> Editing this leave request will reset its status to pending and require re-approval from HR.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('leaves.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save"></i> Update Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-update end date minimum when start date changes
    document.getElementById('start_date').addEventListener('change', function() {
        document.getElementById('end_date').setAttribute('min', this.value);
    });
</script>
@endpush
@endsection
