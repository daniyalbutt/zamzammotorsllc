@extends('layouts.app')

@section('title', 'Payroll Details')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><i class="bi bi-file-text"></i> Payroll Details</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Employee Information</h6>
                            <p class="mb-1">
                                <strong>Name:</strong> {{ $payroll->employee->user->name }}
                            </p>
                            <p class="mb-1">
                                <strong>Employee ID:</strong> {{ $payroll->employee->employee_id }}
                            </p>
                            <p class="mb-1">
                                <strong>Email:</strong> {{ $payroll->employee->user->email }}
                            </p>
                            <p class="mb-1">
                                <strong>Department:</strong> {{ $payroll->employee->department->name ?? 'N/A' }}
                            </p>
                            <p class="mb-1">
                                <strong>Designation:</strong> {{ $payroll->employee->designation ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted">Payroll Information</h6>
                            <p class="mb-1">
                                <strong>Month/Year:</strong>
                                <span class="badge bg-primary">
                                    {{ \Carbon\Carbon::parse($payroll->month_year)->format('F Y') }}
                                </span>
                            </p>
                            <p class="mb-1">
                                <strong>Status:</strong>
                                @if($payroll->status === 'Pending')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clock"></i> Pending
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Paid
                                    </span>
                                @endif
                            </p>
                            <p class="mb-1">
                                <strong>Generated On:</strong> {{ $payroll->created_at->format('M d, Y h:i A') }}
                            </p>
                            @if($payroll->payment_date)
                                <p class="mb-1">
                                    <strong>Payment Date:</strong> {{ $payroll->payment_date->format('M d, Y') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-muted mb-3">Salary Breakdown</h6>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td class="bg-light"><strong>Basic Salary</strong></td>
                                            <td class="text-end">
                                                <span class="fs-5">${{ number_format($payroll->basic_salary, 2) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light"><strong>Bonus / Incentives</strong></td>
                                            <td class="text-end text-success">
                                                <span class="fs-5">+ ${{ number_format($payroll->bonus, 2) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light"><strong>Deductions</strong></td>
                                            <td class="text-end text-danger">
                                                <span class="fs-5">- ${{ number_format($payroll->deductions, 2) }}</span>
                                            </td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td><strong class="fs-5">Net Salary</strong></td>
                                            <td class="text-end">
                                                <strong class="fs-4 text-primary">
                                                    ${{ number_format($payroll->net_salary, 2) }}
                                                </strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if($payroll->notes)
                        <hr>
                        <div class="mb-3">
                            <h6 class="text-muted">Notes</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $payroll->notes }}
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>

                        <div>
                            @can('edit payroll')
                                @if($payroll->status === 'Pending')
                                    <a href="{{ route('payrolls.edit', $payroll) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>

                                    <form action="{{ route('payrolls.mark-paid', $payroll) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Mark this payroll as paid?')">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-check-lg"></i> Mark as Paid
                                        </button>
                                    </form>

                                    <form action="{{ route('payrolls.destroy', $payroll) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this payroll?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
