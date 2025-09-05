@extends('layouts.app')

@section('title', 'Create Payslip')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Create Employee Payslip</h4>
                        <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back to Payslips
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('payroll.store') }}" method="POST" id="payslipForm">
                        @csrf
                        
                        <div class="row mb-4">
                            <!-- Employee Selection -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id" class="form-label required">Select Employee</label>
                                    <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                        <option value="">Choose Employee...</option>
                                        {{-- @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('user_id') == $employee->id ? 'selected' : '' }}
                                                    data-name="{{ $employee->name }}"
                                                    data-email="{{ $employee->email }}"
                                                    data-phone="{{ $employee->phone ?? '+1(800) 642 7676' }}"
                                                    data-position="{{ $employee->position ?? 'Employee' }}"
                                                    data-department="{{ $employee->department ?? 'General Department' }}">
                                                {{ $employee->name }} ({{ $employee->email }})
                                            </option>
                                        @endforeach --}}
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Month Selection -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="month" class="form-label required">Month</label>
                                    <select name="month" id="month" class="form-select @error('month') is-invalid @enderror" required>
                                        <option value="">Select Month</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('month', date('n')) == $i ? 'selected' : '' }}>
                                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Year (Optional) -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="year" class="form-label">Year</label>
                                    <input type="number" name="year" id="year" class="form-control" 
                                           value="{{ old('year', date('Y')) }}" min="2020" max="2030">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <!-- Date Issued -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_issued" class="form-label">Date Issued</label>
                                    <input type="date" name="date_issued" id="date_issued" 
                                           class="form-control @error('date_issued') is-invalid @enderror"
                                           value="{{ old('date_issued', date('Y-m-d')) }}">
                                    @error('date_issued')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Due Date -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="due_date" class="form-label">Due Date</label>
                                    <input type="date" name="due_date" id="due_date" 
                                           class="form-control @error('due_date') is-invalid @enderror"
                                           value="{{ old('due_date', date('Y-m-d')) }}">
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Employee Preview -->
                        <div class="row mb-4" id="employeePreview" style="display: none;">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Selected Employee Details</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Name:</strong> <span id="preview-name"></span></p>
                                                <p class="mb-1"><strong>Position:</strong> <span id="preview-position"></span></p>
                                                <p class="mb-1"><strong>Department:</strong> <span id="preview-department"></span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Email:</strong> <span id="preview-email"></span></p>
                                                <p class="mb-1"><strong>Phone:</strong> <span id="preview-phone"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-success text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Earnings</h5>
                                            <button type="button" class="btn btn-light btn-sm" id="addEarning">
                                                <i class="fa fa-plus"></i> Add Earning
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="earningsContainer">
                                            <!-- Earnings will be added here dynamically -->
                                            <div class="earning-row mb-3">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <input type="text" name="earnings[0][name]" class="form-control" 
                                                               placeholder="Earning Name" value="Basic Salary" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="text" name="earnings[0][description]" class="form-control" 
                                                               placeholder="Description" value="">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <select name="earnings[0][pay_type]" class="form-select">
                                                            <option value="-">-</option>
                                                            <option value="Fixed">Fixed</option>
                                                            <option value="Percentage">Percentage</option>
                                                            <option value="As Per Need">As Per Need</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" name="earnings[0][amount]" class="form-control earning-amount" 
                                                               placeholder="Amount" step="0.01" required>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-danger btn-sm remove-earning" style="display: none;">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-9 text-end">
                                                <strong>Total Earnings:</strong>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-control-plaintext">
                                                    <strong>$<span id="totalEarnings">0.00</span></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deductions Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-warning text-dark">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Deductions</h5>
                                            <button type="button" class="btn btn-dark btn-sm" id="addDeduction">
                                                <i class="fa fa-plus"></i> Add Deduction
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="deductionsContainer">
                                            <!-- Deductions will be added here dynamically -->
                                            <div class="deduction-row mb-3">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <input type="text" name="deductions[0][name]" class="form-control" 
                                                               placeholder="Deduction Name" value="Provident Fund (PF)" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="text" name="deductions[0][description]" class="form-control" 
                                                               placeholder="Description" value="After retirement">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <select name="deductions[0][pay_type]" class="form-select">
                                                            <option value="-">-</option>
                                                            <option value="Fixed" selected>Fixed</option>
                                                            <option value="Percentage">Percentage</option>
                                                            <option value="As Per Need">As Per Need</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" name="deductions[0][amount]" class="form-control deduction-amount" 
                                                               placeholder="Amount" step="0.01" required>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-danger btn-sm remove-deduction" style="display: none;">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-9 text-end">
                                                <strong>Total Deductions:</strong>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-control-plaintext">
                                                    <strong>$<span id="totalDeductions">0.00</span></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <h6>Gross Salary: $<span id="grossSalary">0.00</span></h6>
                                            </div>
                                            <div class="col-md-4">
                                                <h6>Total Deductions: $<span id="summaryDeductions">0.00</span></h6>
                                            </div>
                                            <div class="col-md-4">
                                                <h5><strong>Net Salary: $<span id="netSalary">0.00</span></strong></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden field for total -->
                        <input type="hidden" name="total" id="totalAmount" value="0">

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Create Payslip
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.required::after {
    content: " *";
    color: red;
}

.earning-row, .deduction-row {
    border-left: 4px solid #28a745;
    padding-left: 10px;
}

.deduction-row {
    border-left-color: #ffc107;
}

.form-control-plaintext {
    padding-top: 0.375rem;
    padding-bottom: 0.375rem;
}
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    let earningIndex = 1;
    let deductionIndex = 1;

    // Employee selection change
    $('#user_id').change(function() {
        const selectedOption = $(this).find('option:selected');
        if (selectedOption.val()) {
            $('#preview-name').text(selectedOption.data('name'));
            $('#preview-email').text(selectedOption.data('email'));
            $('#preview-phone').text(selectedOption.data('phone'));
            $('#preview-position').text(selectedOption.data('position'));
            $('#preview-department').text(selectedOption.data('department'));
            $('#employeePreview').show();
        } else {
            $('#employeePreview').hide();
        }
    });

    // Add earning row
    $('#addEarning').click(function() {
        const newRow = `
            <div class="earning-row mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="earnings[${earningIndex}][name]" class="form-control" 
                               placeholder="Earning Name" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="earnings[${earningIndex}][description]" class="form-control" 
                               placeholder="Description">
                    </div>
                    <div class="col-md-2">
                        <select name="earnings[${earningIndex}][pay_type]" class="form-select">
                            <option value="-">-</option>
                            <option value="Fixed">Fixed</option>
                            <option value="Percentage">Percentage</option>
                            <option value="As Per Need">As Per Need</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="earnings[${earningIndex}][amount]" class="form-control earning-amount" 
                               placeholder="Amount" step="0.01" required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-earning">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#earningsContainer').append(newRow);
        earningIndex++;
        updateRemoveButtons();
    });

    // Add deduction row
    $('#addDeduction').click(function() {
        const newRow = `
            <div class="deduction-row mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="deductions[${deductionIndex}][name]" class="form-control" 
                               placeholder="Deduction Name" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="deductions[${deductionIndex}][description]" class="form-control" 
                               placeholder="Description">
                    </div>
                    <div class="col-md-2">
                        <select name="deductions[${deductionIndex}][pay_type]" class="form-select">
                            <option value="-">-</option>
                            <option value="Fixed">Fixed</option>
                            <option value="Percentage">Percentage</option>
                            <option value="As Per Need">As Per Need</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="deductions[${deductionIndex}][amount]" class="form-control deduction-amount" 
                               placeholder="Amount" step="0.01" required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-deduction">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#deductionsContainer').append(newRow);
        deductionIndex++;
        updateRemoveButtons();
    });

    // Remove earning/deduction rows
    $(document).on('click', '.remove-earning', function() {
        $(this).closest('.earning-row').remove();
        updateRemoveButtons();
        calculateTotals();
    });

    $(document).on('click', '.remove-deduction', function() {
        $(this).closest('.deduction-row').remove();
        updateRemoveButtons();
        calculateTotals();
    });

    // Update remove button visibility
    function updateRemoveButtons() {
        $('.remove-earning').toggle($('.earning-row').length > 1);
        $('.remove-deduction').toggle($('.deduction-row').length > 1);
    }

    // Calculate totals
    $(document).on('input', '.earning-amount, .deduction-amount', calculateTotals);

    function calculateTotals() {
        let totalEarnings = 0;
        let totalDeductions = 0;

        $('.earning-amount').each(function() {
            const value = parseFloat($(this).val()) || 0;
            totalEarnings += value;
        });

        $('.deduction-amount').each(function() {
            const value = parseFloat($(this).val()) || 0;
            totalDeductions += value;
        });

        const netSalary = totalEarnings - totalDeductions;

        $('#totalEarnings').text(totalEarnings.toFixed(2));
        $('#totalDeductions').text(totalDeductions.toFixed(2));
        $('#grossSalary').text(totalEarnings.toFixed(2));
        $('#summaryDeductions').text(totalDeductions.toFixed(2));
        $('#netSalary').text(netSalary.toFixed(2));
        $('#totalAmount').val(netSalary.toFixed(2));
    }

    // Form validation
    $('#payslipForm').submit(function(e) {
        let hasEarnings = $('.earning-amount').filter(function() {
            return parseFloat($(this).val()) > 0;
        }).length > 0;

        if (!hasEarnings) {
            e.preventDefault();
            alert('Please add at least one earning with an amount greater than 0.');
            return false;
        }

        // Validate net salary is not negative
        const netSalary = parseFloat($('#netSalary').text());
        if (netSalary < 0) {
            e.preventDefault();
            alert('Net salary cannot be negative. Please check your deductions.');
            return false;
        }
    });

    // Initialize
    updateRemoveButtons();
    calculateTotals();
});
</script>
@endpush
@endsection