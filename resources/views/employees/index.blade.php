@extends('layouts.app')

@section('title', 'Employees')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="bi bi-person-badge"></i> Employees</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('employees.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Employee
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($employees->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Shift</th>
                            <th>Designation</th>
                            <th>Joining Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td><strong>{{ $employee->employee_id }}</strong></td>
                            <td>{{ $employee->user->name }}</td>
                            <td>{{ $employee->department->name }}</td>
                            <td>{{ $employee->shift->name }}</td>
                            <td>{{ $employee->designation }}</td>
                            <td>{{ $employee->joining_date->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $employees->links() }}
        @else
            <div class="alert alert-info mb-0">
                <i class="bi bi-info-circle"></i> No employees found.
            </div>
        @endif
    </div>
</div>
@endsection
