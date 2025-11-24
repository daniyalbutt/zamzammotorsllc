@extends('layouts.app')

@section('title', 'Edit Payroll')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h4 class="mb-0"><i class="bi bi-pencil"></i> Edit Payroll</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('payrolls.update', $payroll) }}" method="POST" id="payrollForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Employee</label>
                            <input type="text"
                                   class="form-control bg-light"
                                   value="{{ $payroll->employee->user->name }} ({{ $payroll->employee->employee_id }})"
                                   readonly>
                            <small class="text-muted">Employee cannot be changed</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Month/Year</label>
                            <input type="text"
                                   class="form-control bg-light"
                                   value="{{ \Carbon\Carbon::parse($payroll->month_year)->format('F Y') }}"
                                   readonly>
                            <small class="text-muted">Month/Year cannot be changed</small>
                        </div>

                        <div class="mb-3">
                            <label for="basic_salary" class="form-label">
                                Basic Salary <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number"
                                       name="basic_salary"
                                       id="basic_salary"
                                       class="form-control @error('basic_salary') is-invalid @enderror"
                                       value="{{ old('basic_salary', $payroll->basic_salary) }}"
                                       min="0"
                                       step="0.01"
                                       required>
                                @error('basic_salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="bonus" class="form-label">Bonus</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number"
                                       name="bonus"
                                       id="bonus"
                                       class="form-control @error('bonus') is-invalid @enderror"
                                       value="{{ old('bonus', $payroll->bonus) }}"
                                       min="0"
                                       step="0.01">
                                @error('bonus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deductions" class="form-label">Deductions</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number"
                                       name="deductions"
                                       id="deductions"
                                       class="form-control @error('deductions') is-invalid @enderror"
                                       value="{{ old('deductions', $payroll->deductions) }}"
                                       min="0"
                                       step="0.01">
                                @error('deductions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Net Salary</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text"
                                       id="net_salary"
                                       class="form-control bg-light"
                                       value="{{ number_format($payroll->net_salary, 2) }}"
                                       readonly>
                            </div>
                            <small class="text-muted">Calculated automatically</small>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes"
                                      id="notes"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      rows="3"
                                      maxlength="500"
                                      placeholder="Optional notes or remarks...">{{ old('notes', $payroll->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save"></i> Update Payroll
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
    // Calculate net salary
    function calculateNetSalary() {
        const basicSalary = parseFloat(document.getElementById('basic_salary').value) || 0;
        const bonus = parseFloat(document.getElementById('bonus').value) || 0;
        const deductions = parseFloat(document.getElementById('deductions').value) || 0;
        const netSalary = basicSalary + bonus - deductions;
        document.getElementById('net_salary').value = netSalary.toFixed(2);
    }

    // Recalculate on input change
    document.getElementById('basic_salary').addEventListener('input', calculateNetSalary);
    document.getElementById('bonus').addEventListener('input', calculateNetSalary);
    document.getElementById('deductions').addEventListener('input', calculateNetSalary);

    // Initial calculation
    calculateNetSalary();
</script>
@endpush
@endsection
