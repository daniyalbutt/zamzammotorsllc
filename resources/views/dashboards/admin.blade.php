@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-speedometer2"></i> Super Admin Dashboard</h2>
        <p class="text-muted">Complete system overview and management</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Total Vehicles</h6>
                        <h3 class="card-title mb-0">{{ $stats['total_vehicles'] }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-car-front-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Available</h6>
                        <h3 class="card-title mb-0">{{ $stats['available_vehicles'] }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-check-circle-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Reserved</h6>
                        <h3 class="card-title mb-0">{{ $stats['reserved_vehicles'] }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-clock-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Sold</h6>
                        <h3 class="card-title mb-0">{{ $stats['sold_vehicles'] }}</h3>
                    </div>
                    <div class="text-danger">
                        <i class="bi bi-check2-all" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue & Customer Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Total Revenue</h6>
                        <h3 class="card-title mb-0">${{ number_format($stats['total_revenue'], 2) }}</h3>
                    </div>
                    <div class="text-info">
                        <i class="bi bi-cash-stack" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-secondary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Total Customers</h6>
                        <h3 class="card-title mb-0">{{ $stats['total_customers'] }}</h3>
                    </div>
                    <div class="text-secondary">
                        <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Active Employees</h6>
                        <h3 class="card-title mb-0">{{ $stats['total_employees'] }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-person-badge-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Section -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-car-front"></i> Recent Vehicles</h5>
            </div>
            <div class="card-body">
                @if($recent_vehicles->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Year</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_vehicles as $vehicle)
                                <tr>
                                    <td>{{ $vehicle->title }}</td>
                                    <td>{{ $vehicle->year }}</td>
                                    <td>
                                        @if($vehicle->availability == 'Available')
                                            <span class="badge bg-success">Available</span>
                                        @elseif($vehicle->availability == 'Reserved')
                                            <span class="badge bg-warning">Reserved</span>
                                        @else
                                            <span class="badge bg-danger">Sold Out</span>
                                        @endif
                                    </td>
                                    <td>${{ number_format($vehicle->price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('vehicles.index') }}" class="btn btn-sm btn-outline-primary mt-2">View All Vehicles</a>
                @else
                    <p class="text-muted mb-0">No vehicles added yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-receipt"></i> Recent Invoices</h5>
            </div>
            <div class="card-body">
                @if($recent_invoices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Invoice#</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->customer->user->name }}</td>
                                    <td>${{ number_format($invoice->vehicle_price, 2) }}</td>
                                    <td>
                                        @if($invoice->status == 'Paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($invoice->status == 'Partial')
                                            <span class="badge bg-warning">Partial</span>
                                        @else
                                            <span class="badge bg-secondary">Unpaid</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-outline-primary mt-2">View All Invoices</a>
                @else
                    <p class="text-muted mb-0">No invoices created yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-lightning-fill"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-3">
                        <a href="{{ route('vehicles.create') }}" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle"></i> Add New Vehicle
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('customers.create') }}" class="btn btn-success w-100">
                            <i class="bi bi-person-plus"></i> Add New Customer
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('employees.create') }}" class="btn btn-info w-100">
                            <i class="bi bi-person-badge"></i> Add New Employee
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('invoices.create') }}" class="btn btn-warning w-100">
                            <i class="bi bi-file-earmark-plus"></i> Create Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
