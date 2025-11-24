@extends('layouts.app')

@section('title', 'Add Employee')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-person-plus"></i> Add New Employee</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('employees.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="user_id" class="form-label">
                                    User Account <span class="text-danger">*</span>
                                </label>
                                <select name="user_id"
                                        id="user_id"
                                        class="form-select @error('user_id') is-invalid @enderror"
                                        required>
                                    <option value="">Select user...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Select an existing user account to link to this employee</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="employee_id" class="form-label">
                                    Employee ID <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="employee_id"
                                       id="employee_id"
                                       class="form-control @error('employee_id') is-invalid @enderror"
                                       value="{{ old('employee_id') }}"
                                       placeholder="e.g., EMP001"
                                       required>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="department_id" class="form-label">
                                    Department <span class="text-danger">*</span>
                                </label>
                                <select name="department_id"
                                        id="department_id"
                                        class="form-select @error('department_id') is-invalid @enderror"
                                        required>
                                    <option value="">Select department...</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}"
                                                {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="shift_id" class="form-label">
                                    Shift <span class="text-danger">*</span>
                                </label>
                                <select name="shift_id"
                                        id="shift_id"
                                        class="form-select @error('shift_id') is-invalid @enderror"
                                        required>
                                    <option value="">Select shift...</option>
                                    @foreach($shifts as $shift)
                                        <option value="{{ $shift->id }}"
                                                {{ old('shift_id') == $shift->id ? 'selected' : '' }}>
                                            {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('shift_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="designation" class="form-label">
                                    Designation <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="designation"
                                       id="designation"
                                       class="form-control @error('designation') is-invalid @enderror"
                                       value="{{ old('designation') }}"
                                       placeholder="e.g., Sales Executive"
                                       required>
                                @error('designation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="joining_date" class="form-label">
                                    Joining Date <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       name="joining_date"
                                       id="joining_date"
                                       class="form-control @error('joining_date') is-invalid @enderror"
                                       value="{{ old('joining_date') }}"
                                       required>
                                @error('joining_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="salary" class="form-label">
                                Monthly Salary <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number"
                                       name="salary"
                                       id="salary"
                                       class="form-control @error('salary') is-invalid @enderror"
                                       value="{{ old('salary') }}"
                                       min="0"
                                       step="0.01"
                                       placeholder="0.00"
                                       required>
                                @error('salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Note:</strong> Make sure the user account exists before creating an employee record. The employee will inherit the user's basic information.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Add Employee
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
