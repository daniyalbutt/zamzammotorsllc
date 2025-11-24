@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h4 class="mb-0"><i class="bi bi-pencil"></i> Edit Employee</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('employees.update', $employee) }}" method="POST">
                        @csrf
                        @method('PUT')

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
                                        <option value="{{ $user->id }}"
                                                {{ old('user_id', $employee->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="employee_id" class="form-label">
                                    Employee ID <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="employee_id"
                                       id="employee_id"
                                       class="form-control @error('employee_id') is-invalid @enderror"
                                       value="{{ old('employee_id', $employee->employee_id) }}"
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
                                                {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
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
                                                {{ old('shift_id', $employee->shift_id) == $shift->id ? 'selected' : '' }}>
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
                                       value="{{ old('designation', $employee->designation) }}"
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
                                       value="{{ old('joining_date', $employee->joining_date->format('Y-m-d')) }}"
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
                                       value="{{ old('salary', $employee->salary) }}"
                                       min="0"
                                       step="0.01"
                                       required>
                                @error('salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save"></i> Update Employee
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
