@extends('layouts.app')

@section('title', 'Generate Payroll')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-cash-stack"></i> Generate Payroll</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('payrolls.store') }}" method="POST" id="payrollForm">
                        @csrf

                        <div class="mb-3">
                            <label for="employee_id" class="form-label">
                                Employee <span class="text-danger">*</span>
                            </label>
                            <select name="employee_id"
                                    id="employee_id"
                                    class="form-select @error('employee_id') is-invalid @enderror"
                                    required>
                                <option value="">Select employee...</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                            data-salary="{{ $employee->salary }}"
                                            {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->user->name }} ({{ $employee->employee_id }})
                                        - ${{ number_format($employee->salary, 2) }}/month
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="month_year" class="form-label">
                                Month/Year <span class="text-danger">*</span>
                            </label>
                            <input type="month"
                                   name="month_year"
                                   id="month_year"
                                   class="form-control @error('month_year') is-invalid @enderror"
                                   value="{{ old('month_year') }}"
                                   required>
                            @error('month_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                       value="{{ old('basic_salary') }}"
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
                                       value="{{ old('bonus', 0) }}"
                                       min="0"
                                       step="0.01">
                                @error('bonus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Optional: Additional bonuses or incentives</small>
                        </div>

                        <div class="mb-3">
                            <label for="deductions" class="form-label">Deductions</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number"
                                       name="deductions"
                                       id="deductions"
                                       class="form-control @error('deductions') is-invalid @enderror"
                                       value="{{ old('deductions', 0) }}"
                                       min="0"
                                       step="0.01">
                                @error('deductions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Optional: Tax, insurance, or other deductions</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Net Salary</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text"
                                       id="net_salary"
                                       class="form-control bg-light"
                                       value="0.00"
                                       readonly>
                            </div>
                            <small class="text-muted">Calculated automatically: Basic Salary + Bonus - Deductions</small>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes"
                                      id="notes"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      rows="3"
                                      maxlength="500"
                                      placeholder="Optional notes or remarks...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maximum 500 characters</small>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Note:</strong> The payroll will be created with "Pending" status. You can mark it as paid later.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Generate Payroll
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
    // Auto-fill basic salary when employee is selected
    document.getElementById('employee_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const salary = selectedOption.getAttribute('data-salary');
        if (salary) {
            document.getElementById('basic_salary').value = salary;
            calculateNetSalary();
        }
    });

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
