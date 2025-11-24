@extends('layouts.app')

@section('title', 'Leave Request Details')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><i class="bi bi-file-text"></i> Leave Request Details</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Employee Information</h6>
                            <p class="mb-1">
                                <strong>Name:</strong> {{ $leave->employee->user->name }}
                            </p>
                            <p class="mb-1">
                                <strong>Email:</strong> {{ $leave->employee->user->email }}
                            </p>
                            <p class="mb-1">
                                <strong>Position:</strong> {{ $leave->employee->position ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted">Leave Details</h6>
                            <p class="mb-1">
                                <strong>Leave Type:</strong>
                                <span class="badge bg-info">{{ $leave->leave_type }}</span>
                            </p>
                            <p class="mb-1">
                                <strong>Status:</strong>
                                @if($leave->status === 'Pending')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clock"></i> Pending
                                    </span>
                                @elseif($leave->status === 'Approved')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Approved
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle"></i> Rejected
                                    </span>
                                @endif
                            </p>
                            <p class="mb-1">
                                <strong>Requested On:</strong> {{ $leave->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6 class="text-muted">Start Date</h6>
                            <p class="fs-5 fw-bold text-primary">
                                <i class="bi bi-calendar-event"></i>
                                {{ $leave->start_date->format('M d, Y') }}
                            </p>
                        </div>

                        <div class="col-md-4">
                            <h6 class="text-muted">End Date</h6>
                            <p class="fs-5 fw-bold text-primary">
                                <i class="bi bi-calendar-event"></i>
                                {{ $leave->end_date->format('M d, Y') }}
                            </p>
                        </div>

                        <div class="col-md-4">
                            <h6 class="text-muted">Total Days</h6>
                            <p class="fs-5 fw-bold text-success">
                                <i class="bi bi-calendar-range"></i>
                                {{ $leave->days }} day(s)
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="text-muted">Reason for Leave</h6>
                        <div class="bg-light p-3 rounded">
                            {{ $leave->reason }}
                        </div>
                    </div>

                    @if($leave->status === 'Approved' || $leave->status === 'Rejected')
                        <hr>

                        <div class="mb-3">
                            <h6 class="text-muted">
                                @if($leave->status === 'Approved')
                                    Approved By
                                @else
                                    Rejected By
                                @endif
                            </h6>
                            <p class="mb-1">
                                <strong>Name:</strong> {{ $leave->approver->name ?? 'N/A' }}
                            </p>
                            <p class="mb-1">
                                <strong>Date:</strong> {{ $leave->approved_at ? $leave->approved_at->format('M d, Y h:i A') : 'N/A' }}
                            </p>
                        </div>

                        @if($leave->status === 'Rejected' && $leave->rejection_reason)
                            <div class="alert alert-danger">
                                <h6 class="alert-heading">
                                    <i class="bi bi-exclamation-triangle"></i> Rejection Reason
                                </h6>
                                {{ $leave->rejection_reason }}
                            </div>
                        @endif
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>

                        <div>
                            @if(auth()->user()->employee && auth()->user()->employee->id === $leave->employee_id && $leave->status === 'Pending')
                                <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <form action="{{ route('leaves.destroy', $leave) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this leave request?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            @endif

                            @if(auth()->user()->hasAnyRole(['Super Admin', 'HR']) && $leave->status === 'Pending')
                                <button type="button"
                                        class="btn btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#approveModal">
                                    <i class="bi bi-check-lg"></i> Approve
                                </button>

                                <button type="button"
                                        class="btn btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectModal">
                                    <i class="bi bi-x-lg"></i> Reject
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
@if(auth()->user()->hasAnyRole(['Super Admin', 'HR']) && $leave->status === 'Pending')
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Leave Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve this leave request?</p>
                    <p><strong>Employee:</strong> {{ $leave->employee->user->name }}</p>
                    <p><strong>Dates:</strong> {{ $leave->start_date->format('M d, Y') }} to {{ $leave->end_date->format('M d, Y') }}</p>
                    <p><strong>Days:</strong> {{ $leave->days }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('leaves.approve', $leave) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg"></i> Approve
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('leaves.reject', $leave) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Leave Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Employee:</strong> {{ $leave->employee->user->name }}</p>
                        <p><strong>Dates:</strong> {{ $leave->start_date->format('M d, Y') }} to {{ $leave->end_date->format('M d, Y') }}</p>

                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">
                                Rejection Reason <span class="text-danger">*</span>
                            </label>
                            <textarea name="rejection_reason"
                                      id="rejection_reason"
                                      class="form-control"
                                      rows="3"
                                      required
                                      placeholder="Please provide a reason for rejection..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-lg"></i> Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection
