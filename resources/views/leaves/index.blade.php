@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-calendar-x"></i> Leave Requests</h2>
        @if(auth()->user()->employee)
            <a href="{{ route('leaves.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Request Leave
            </a>
        @endif
    </div>

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

    <div class="card shadow-sm">
        <div class="card-body">
            @if($leaves->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x" style="font-size: 4rem;"></i>
                    <p class="mt-3">No leave requests found.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Leave Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Reason</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaves as $leave)
                                <tr>
                                    <td>
                                        <strong>{{ $leave->employee->user->name }}</strong><br>
                                        <small class="text-muted">{{ $leave->employee->user->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $leave->leave_type }}</span>
                                    </td>
                                    <td>{{ $leave->start_date->format('M d, Y') }}</td>
                                    <td>{{ $leave->end_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $leave->days }} day(s)</span>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($leave->reason, 50) }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('leaves.show', $leave) }}"
                                               class="btn btn-outline-primary"
                                               title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            @if(auth()->user()->employee && auth()->user()->employee->id === $leave->employee_id && $leave->status === 'Pending')
                                                <a href="{{ route('leaves.edit', $leave) }}"
                                                   class="btn btn-outline-warning"
                                                   title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                <form action="{{ route('leaves.destroy', $leave) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this leave request?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-outline-danger"
                                                            title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if(auth()->user()->hasAnyRole(['Super Admin', 'HR']) && $leave->status === 'Pending')
                                                <button type="button"
                                                        class="btn btn-outline-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#approveModal{{ $leave->id }}"
                                                        title="Approve">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>

                                                <button type="button"
                                                        class="btn btn-outline-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#rejectModal{{ $leave->id }}"
                                                        title="Reject">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Approve Modal -->
                                @if(auth()->user()->hasAnyRole(['Super Admin', 'HR']) && $leave->status === 'Pending')
                                    <div class="modal fade" id="approveModal{{ $leave->id }}" tabindex="-1">
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
                                    <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1">
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
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $leaves->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
