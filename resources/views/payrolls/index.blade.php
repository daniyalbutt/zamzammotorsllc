@extends('layouts.app')

@section('title', 'Payroll Management')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-cash-stack"></i> Payroll Management</h2>
        @can('edit payroll')
            <div>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkGenerateModal">
                    <i class="bi bi-plus-circle-fill"></i> Bulk Generate
                </button>
                <a href="{{ route('payrolls.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Generate Payroll
                </a>
            </div>
        @endcan
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

    <!-- Filters -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('payrolls.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="month_year" class="form-label">Month/Year</label>
                    <select name="month_year" id="month_year" class="form-select">
                        <option value="">All Months</option>
                        @foreach($months as $month)
                            <option value="{{ $month }}" {{ request('month_year') === $month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($month)->format('F Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Paid" {{ request('status') === 'Paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($payrolls->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-cash-stack" style="font-size: 4rem;"></i>
                    <p class="mt-3">No payroll records found.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Month/Year</th>
                                <th>Basic Salary</th>
                                <th>Bonus</th>
                                <th>Deductions</th>
                                <th>Net Salary</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payrolls as $payroll)
                                <tr>
                                    <td>
                                        <strong>{{ $payroll->employee->user->name }}</strong><br>
                                        <small class="text-muted">{{ $payroll->employee->employee_id }}</small>
                                    </td>
                                    <td>{{ $payroll->employee->department->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($payroll->month_year)->format('F Y') }}</td>
                                    <td>${{ number_format($payroll->basic_salary, 2) }}</td>
                                    <td class="text-success">${{ number_format($payroll->bonus, 2) }}</td>
                                    <td class="text-danger">${{ number_format($payroll->deductions, 2) }}</td>
                                    <td>
                                        <strong class="text-primary">${{ number_format($payroll->net_salary, 2) }}</strong>
                                    </td>
                                    <td>
                                        @if($payroll->status === 'Pending')
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-clock"></i> Pending
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Paid
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('payrolls.show', $payroll) }}"
                                               class="btn btn-outline-primary"
                                               title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            @can('edit payroll')
                                                @if($payroll->status === 'Pending')
                                                    <a href="{{ route('payrolls.edit', $payroll) }}"
                                                       class="btn btn-outline-warning"
                                                       title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>

                                                    <form action="{{ route('payrolls.mark-paid', $payroll) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Mark this payroll as paid?')">
                                                        @csrf
                                                        <button type="submit"
                                                                class="btn btn-outline-success"
                                                                title="Mark as Paid">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('payrolls.destroy', $payroll) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this payroll?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-outline-danger"
                                                                title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="6" class="text-end"><strong>Total:</strong></td>
                                <td colspan="3">
                                    <strong class="text-primary">
                                        ${{ number_format($payrolls->sum('net_salary'), 2) }}
                                    </strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $payrolls->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Generate Modal -->
@can('edit payroll')
    <div class="modal fade" id="bulkGenerateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('payrolls.bulk-generate') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Bulk Generate Payroll</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>This will generate payroll for all employees who don't have a payroll record for the selected month.</p>

                        <div class="mb-3">
                            <label for="bulk_month_year" class="form-label">
                                Select Month/Year <span class="text-danger">*</span>
                            </label>
                            <input type="month"
                                   name="month_year"
                                   id="bulk_month_year"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Note:</strong> Basic salary will be taken from each employee's profile.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-plus-circle-fill"></i> Generate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
@endsection
