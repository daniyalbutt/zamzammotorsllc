@extends('layouts.app')

@section('title', isset($data) ? 'Edit Payslip' : 'Create Payslip')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">{{ isset($data) ? 'Edit' : 'Create' }} Employee Payslip</h4>
                            <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back to Payslips
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ isset($data) ? route('payroll.update', $data->id) : route('payroll.store') }}"
                            method="POST" id="payslipForm">
                            @csrf
                            @if (isset($data))
                                @method('PUT')
                            @endif

                            <div class="row mb-4">
                                <!-- Employee Selection -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="user_id" class="form-label required">Select Employee</label>
                                        <select name="user_id" id="user_id"
                                            class="form-select @error('user_id') is-invalid @enderror" required
                                            {{ isset($data) ? 'disabled' : '' }}>
                                            ? 'disabled' : '' }}>
                                            @if (!isset($data))
                                                <option value="">Choose Employee...</option>
                                                @foreach ($employee as $emp)
                                                    <option value="{{ $emp->id }}"
                                                        {{ old('user_id', isset($data) ? $data->user_id : '') == $emp->id ? 'selected' : '' }}
                                                        data-name="{{ $emp->name }}" data-email="{{ $emp->email }}"
                                                        data-phone="{{ $emp->phone ?? '+1(800) 642 7676' }}"
                                                        data-position="{{ $emp->getMeta('designation') }}"
                                                        data-department="{{ $emp->getDepartment() }}">
                                                        {{ $emp->name }} ({{ $emp->email }})
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="{{ $data->user->id }}" selected>
                                                    {{ $data->user->name }} ({{ $data->user->email }})
                                                </option>
                                            @endif

                                        </select>
                                        @if (isset($data))
                                            <input type="hidden" name="user_id" value="{{ $data->user_id }}">
                                        @endif
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Month Selection -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="month" class="form-label required">Month</label>
                                        <select name="month" id="month"
                                            class="form-select @error('month') is-invalid @enderror" required>
                                            <option value="">Select Month</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}"
                                                    {{ old('month', isset($data) ? date('n', strtotime($data->month_year)) : date('n')) == $i ? 'selected' : '' }}>
                                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('month')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Year -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="status" class="form-label required">Status</label>
                                        <select name="status" id="status"
                                            class="form-select" required>
                                            <option value="pending">Pending</option>
                                            <option value="unpaid">Unpaid</option>
                                            <option value="paid">Paid</option>
                                            
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
                                            value="{{ old('date_issued', isset($data) ? $data->date_issued : date('Y-m-d')) }}">
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
                                            value="{{ old('due_date', isset($data) ? $data->due_date : date('Y-m-d')) }}">
                                        @error('due_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Employee Preview -->
                            <div class="row mb-4" id="employeePreview" style="{{ isset($data) ? '' : 'display: none;' }}">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">Selected Employee Details</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="mb-1"><strong>Name:</strong> <span
                                                            id="preview-name">{{ isset($data) ? $data->user->name : '' }}</span>
                                                    </p>
                                                    <p class="mb-1"><strong>Position:</strong> <span
                                                            id="preview-position">{{ isset($data) ? $data->user->getMeta('designation') : '' }}</span>
                                                    </p>
                                                    <p class="mb-1"><strong>Department:</strong> <span
                                                            id="preview-department">{{ isset($data) ? $data->user->getDepartment() : '' }}</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-1"><strong>Email:</strong> <span
                                                            id="preview-email">{{ isset($data) ? $data->user->email : '' }}</span>
                                                    </p>
                                                    <p class="mb-1"><strong>Phone:</strong> <span
                                                            id="preview-phone">{{ isset($data) ? $data->user->phone ?? '+1(800) 642 7676' : '' }}</span>
                                                    </p>
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
                                                @if (isset($data) && $data->payslipsData->where('type', 'earning')->count() > 0)
                                                    @foreach ($data->payslipsData->where('type', 'earning') as $index => $earning)
                                                        <div class="earning-row mb-3">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <input type="text"
                                                                        name="earnings[{{ $index }}][name]"
                                                                        class="form-control" placeholder="Earning Name"
                                                                        value="{{ $earning->name }}" required>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="text"
                                                                        name="earnings[{{ $index }}][description]"
                                                                        class="form-control" placeholder="Description"
                                                                        value="{{ $earning->description }}">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <select name="earnings[{{ $index }}][pay_type]"
                                                                        class="form-select">
                                                                        <option value="-"
                                                                            {{ $earning->pay_type == '-' ? 'selected' : '' }}>
                                                                            -</option>
                                                                        <option value="Fixed"
                                                                            {{ $earning->pay_type == 'Fixed' ? 'selected' : '' }}>
                                                                            Fixed</option>
                                                                        <option value="Percentage"
                                                                            {{ $earning->pay_type == 'Percentage' ? 'selected' : '' }}>
                                                                            Percentage</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="number"
                                                                        name="earnings[{{ $index }}][amount]"
                                                                        class="form-control earning-amount"
                                                                        value="{{ $earning->amount }}"
                                                                        placeholder="Amount" step="0.01" required>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <button type="button"
                                                                        onclick="deleteEarningRow(this.closest('.earning-row'))"
                                                                        class="btn btn-danger btn-sm remove-earning"
                                                                        style="{{ $loop->first && $data->payslipsData->where('type', 'earning')->count() == 1 ? 'display: none;' : '' }}">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="earning-row mb-3">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <input type="text" name="earnings[0][name]"
                                                                    class="form-control" placeholder="Earning Name"
                                                                    value="Basic Salary" required>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" name="earnings[0][description]"
                                                                    class="form-control" placeholder="Description"
                                                                    value="">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <select name="earnings[0][pay_type]" class="form-select">
                                                                    <option value="-">-</option>
                                                                    <option value="Fixed">Fixed</option>
                                                                    <option value="Percentage">Percentage</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="number" name="earnings[0][amount]"
                                                                    class="form-control earning-amount"
                                                                    value="{{ isset($data) ? '' : $employee->getMeta('salary') ?? '' }}"
                                                                    placeholder="Amount" step="0.01" required>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <button type="button"
                                                                    onclick="deleteEarningRow(this.closest('.earning-row'))"
                                                                    class="btn btn-danger btn-sm remove-earning"
                                                                    style="display: none;">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="row">
                                                <div class="col-md-9 text-end">
                                                    <strong>Total Earnings:</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-control-plaintext">
                                                        <strong>$<span
                                                                id="totalEarnings">{{ isset($data) ? $data->payslipsData->where('type', 'earning')->sum('amount') : '0' }}</span></strong>
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
                                                @if (isset($data) && $data->payslipsData->where('type', 'deduction')->count() > 0)
                                                    @foreach ($data->payslipsData->where('type', 'deduction') as $index => $deduction)
                                                        <div class="deduction-row mb-3">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <input type="text"
                                                                        name="deductions[{{ $index }}][name]"
                                                                        class="form-control" placeholder="Deduction Name"
                                                                        value="{{ $deduction->name }}" required>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="text"
                                                                        name="deductions[{{ $index }}][description]"
                                                                        class="form-control" placeholder="Description"
                                                                        value="{{ $deduction->description }}">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <select
                                                                        name="deductions[{{ $index }}][pay_type]"
                                                                        class="form-select">
                                                                        <option value="-"
                                                                            {{ $deduction->pay_type == '-' ? 'selected' : '' }}>
                                                                            -</option>
                                                                        <option value="Fixed"
                                                                            {{ $deduction->pay_type == 'Fixed' ? 'selected' : '' }}>
                                                                            Fixed</option>
                                                                        <option value="Percentage"
                                                                            {{ $deduction->pay_type == 'Percentage' ? 'selected' : '' }}>
                                                                            Percentage</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="number"
                                                                        name="deductions[{{ $index }}][amount]"
                                                                        class="form-control deduction-amount"
                                                                        value="{{ $deduction->amount }}"
                                                                        placeholder="Amount" step="0.01" required>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <button type="button"
                                                                        onclick="deleteDeductionRow(this.closest('.deduction-row'))"
                                                                        class="btn btn-danger btn-sm remove-deduction"
                                                                        style="{{ $loop->first && $data->payslipsData->where('type', 'deduction')->count() == 1 ? 'display: none;' : '' }}">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="deduction-row mb-3">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <input type="text" name="deductions[0][name]"
                                                                    class="form-control" placeholder="Deduction Name"
                                                                    value="Provident Fund (PF)" required>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" name="deductions[0][description]"
                                                                    class="form-control" placeholder="Description"
                                                                    value="After retirement">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <select name="deductions[0][pay_type]"
                                                                    class="form-select">
                                                                    <option value="-">-</option>
                                                                    <option value="Fixed" selected>Fixed</option>
                                                                    <option value="Percentage">Percentage</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="number" name="deductions[0][amount]"
                                                                    class="form-control deduction-amount"
                                                                    placeholder="Amount" step="0.01" required>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm remove-deduction"
                                                                    style="display: none;">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="row">
                                                <div class="col-md-9 text-end">
                                                    <strong>Total Deductions:</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-control-plaintext">
                                                        <strong>$<span
                                                                id="totalDeductions">{{ isset($data) ? $data->payslipsData->where('type', 'deduction')->sum('amount') : '0' }}</span></strong>
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
                                                    <h6>Total Deductions: $<span
                                                            id="summaryDeductions">{{ $data ? $data->payslipsData->where('type', 'deduction')->sum('amount') : '0' }}</span>
                                                    </h6>
                                                </div>
                                                <div class="col-md-4">
                                                    <h5><strong>Net Salary: $<span
                                                                id="netSalary">{{ isset($data) ? $data->total : '0' }}</span></strong>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden field for total -->
                            <input type="hidden" name="total" id="totalAmount"
                                value="{{ isset($data) ? $data->total : '0' }}">

                            <!-- Submit Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> {{ isset($data) ? 'Update' : 'Create' }} Payslip
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

            .earning-row,
            .deduction-row {
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
            // Define these functions in the global scope
            function calculateTotals() {
                let baseEarnings = 0; // Fixed earnings first
                let percentageEarnings = 0;
                let baseDeductions = 0;
                let percentageDeductions = 0;

                // Loop through earnings
                document.querySelectorAll(".earning-row").forEach(row => {
                    const payType = row.querySelector('select[name*="[pay_type]"]').value;
                    const amount = parseFloat(row.querySelector(".earning-amount").value) || 0;

                    if (payType === "Fixed") {
                        baseEarnings += amount;
                    } else if (payType === "Percentage") {
                        percentageEarnings += amount; // store percentage to apply later
                    }
                });

                // Loop through deductions
                document.querySelectorAll(".deduction-row").forEach(row => {
                    const payType = row.querySelector('select[name*="[pay_type]"]').value;
                    const amount = parseFloat(row.querySelector(".deduction-amount").value) || 0;

                    if (payType === "Fixed") {
                        baseDeductions += amount;
                    } else if (payType === "Percentage") {
                        percentageDeductions += amount; // store percentage to apply later
                    }
                });

                // Apply percentage earnings on baseEarnings only
                let totalEarnings = baseEarnings + (baseEarnings * (percentageEarnings / 100));

                // Apply percentage deductions on baseEarnings only
                let totalDeductions = baseDeductions + (baseEarnings * (percentageDeductions / 100));

                const netSalary = totalEarnings - totalDeductions;

                // Update UI
                document.getElementById("totalEarnings").textContent = totalEarnings.toFixed(2);
                document.getElementById("totalDeductions").textContent = totalDeductions.toFixed(2);
                document.getElementById("grossSalary").textContent = totalEarnings.toFixed(2);
                document.getElementById("summaryDeductions").textContent = totalDeductions.toFixed(2);
                document.getElementById("netSalary").textContent = netSalary.toFixed(2);
                document.getElementById("totalAmount").value = netSalary.toFixed(2);
            }

            function updateRemoveButtons() {
                const earningBtns = document.querySelectorAll(".remove-earning");
                const deductionBtns = document.querySelectorAll(".remove-deduction");

                earningBtns.forEach(btn => {
                    btn.style.display = (document.querySelectorAll(".earning-row").length > 1) ?
                        "inline-block" : "none";
                });
                deductionBtns.forEach(btn => {
                    btn.style.display = (document.querySelectorAll(".deduction-row").length > 1) ?
                        "inline-block" : "none";
                });
            }

            const deleteEarningRow = (row) => {
                if (document.querySelectorAll(".earning-row").length > 1) {
                    row.remove();
                    calculateTotals();
                    updateRemoveButtons();
                } else {
                    alert("You must have at least one earning entry.");
                }
            };

            const deleteDeductionRow = (row) => {
                if (document.querySelectorAll(".deduction-row").length > 1) {
                    row.remove();
                    calculateTotals();
                    updateRemoveButtons();
                } else {
                    alert("You must have at least one deduction entry.");
                }
            };

            document.addEventListener("DOMContentLoaded", function() {
                // Set initial indices based on existing rows
                let earningIndex =
                    {{ isset($data) && $data->payslipsData->where('type', 'earning')->count() > 0 ? $data->payslipsData->where('type', 'earning')->count() : 1 }};
                let deductionIndex =
                    {{ isset($data) && $data->payslipsData->where('type', 'deduction')->count() > 0 ? $data->payslipsData->where('type', 'deduction')->count() : 1 }};

                const userSelect = document.getElementById("user_id");
                const employeePreview = document.getElementById("employeePreview");
                const earningsContainer = document.getElementById("earningsContainer");
                const deductionsContainer = document.getElementById("deductionsContainer");
                const payslipForm = document.getElementById("payslipForm");

                // If editing, show employee preview
                @if (isset($data))
                    employeePreview.style.display = "block";
                @endif

                // Employee selection change
                userSelect.addEventListener("change", function() {
                    const selectedOption = userSelect.options[userSelect.selectedIndex];
                    if (selectedOption.value) {
                        document.getElementById("preview-name").textContent = selectedOption.dataset.name;
                        document.getElementById("preview-email").textContent = selectedOption.dataset.email;
                        document.getElementById("preview-phone").textContent = selectedOption.dataset.phone;
                        document.getElementById("preview-position").textContent = selectedOption.dataset
                            .position;
                        document.getElementById("preview-department").textContent = selectedOption.dataset
                            .department;
                        employeePreview.style.display = "block";
                    } else {
                        employeePreview.style.display = "none";
                    }
                });

                // Add earning row
                document.getElementById("addEarning").addEventListener("click", function() {
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
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="earnings[${earningIndex}][amount]" class="form-control earning-amount" 
                                placeholder="Amount" step="0.01" required>
                        </div>
                        <div class="col-md-1">
                            <button type="button" onclick="deleteEarningRow(this.closest('.earning-row'))" class="btn btn-danger btn-sm remove-earning">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
                    earningsContainer.insertAdjacentHTML("beforeend", newRow);

                    // Add event listener to the new amount input
                    const newRowEl = earningsContainer.lastElementChild;
                    newRowEl.querySelector('.earning-amount').addEventListener('input', calculateTotals);
                    newRowEl.querySelector('select[name*="[pay_type]"]').addEventListener('change',
                        calculateTotals);

                    earningIndex++;
                    updateRemoveButtons();
                });

                // Add deduction row
                document.getElementById("addDeduction").addEventListener("click", function() {
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
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="deductions[${deductionIndex}][amount]" class="form-control deduction-amount" 
                                placeholder="Amount" step="0.01" required>
                        </div>
                        <div class="col-md-1">
                            <button type="button" onclick="deleteDeductionRow(this.closest('.deduction-row'))" class="btn btn-danger btn-sm remove-deduction">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
                    deductionsContainer.insertAdjacentHTML("beforeend", newRow);

                    // Add event listener to the new amount input
                    const newRowEl = deductionsContainer.lastElementChild;
                    newRowEl.querySelector('.deduction-amount').addEventListener('input', calculateTotals);
                    newRowEl.querySelector('select[name*="[pay_type]"]').addEventListener('change',
                        calculateTotals);

                    deductionIndex++;
                    updateRemoveButtons();
                });

                // Add event listeners to existing amount inputs
                document.querySelectorAll('.earning-amount, .deduction-amount').forEach(input => {
                    input.addEventListener('input', calculateTotals);
                });

                document.querySelectorAll('select[name*="[pay_type]"]').forEach(select => {
                    select.addEventListener('change', calculateTotals);
                });

                // Form validation
                payslipForm.addEventListener("submit", function(e) {
                    const hasEarnings = Array.from(document.querySelectorAll(".earning-amount"))
                        .some(el => parseFloat(el.value) > 0);

                    if (!hasEarnings) {
                        e.preventDefault();
                        alert("Please add at least one earning with an amount greater than 0.");
                        return;
                    }

                    const netSalary = parseFloat(document.getElementById("netSalary").textContent);
                    if (netSalary < 0) {
                        e.preventDefault();
                        alert("Net salary cannot be negative. Please check your deductions.");
                        return;
                    }
                });

                // Initialize
                updateRemoveButtons();
                calculateTotals();
            });
        </script>
    @endpush
@endsection
