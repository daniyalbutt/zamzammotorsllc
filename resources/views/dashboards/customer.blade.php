@extends('layouts.app')

@section('title', 'Customer Portal')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-person-circle"></i> Customer Portal</h2>
        <p class="text-muted">Welcome, {{ auth()->user()->name }}!</p>
    </div>
</div>

<!-- Quick Access Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <a href="{{ route('customer-portal.my-profile') }}" class="text-decoration-none">
            <div class="card shadow-sm h-100 hover-shadow">
                <div class="card-body text-center">
                    <i class="bi bi-person-circle text-primary" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">My Profile</h5>
                    <p class="text-muted small">Manage your account settings</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 mb-3">
        <a href="{{ route('customer-portal.my-account-info') }}" class="text-decoration-none">
            <div class="card shadow-sm h-100 hover-shadow">
                <div class="card-body text-center">
                    <i class="bi bi-info-circle text-info" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Account Info</h5>
                    <p class="text-muted small">View your account details</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 mb-3">
        <a href="{{ route('customer-portal.my-consignee-details') }}" class="text-decoration-none">
            <div class="card shadow-sm h-100 hover-shadow">
                <div class="card-body text-center">
                    <i class="bi bi-truck text-success" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Consignee Details</h5>
                    <p class="text-muted small">Shipping information</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 mb-3">
        <a href="{{ route('customer-portal.my-favorites') }}" class="text-decoration-none">
            <div class="card shadow-sm h-100 hover-shadow">
                <div class="card-body text-center">
                    <i class="bi bi-heart-fill text-danger" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">My Favorites</h5>
                    <p class="text-muted small">Your saved vehicles</p>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <a href="{{ route('customer-portal.reserved-vehicles') }}" class="text-decoration-none">
            <div class="card shadow-sm h-100 hover-shadow">
                <div class="card-body text-center">
                    <i class="bi bi-bookmark-fill text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Reserved Vehicles</h5>
                    <p class="text-muted small">Vehicles on hold for you</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4 mb-3">
        <a href="{{ route('customer-portal.purchased-vehicles') }}" class="text-decoration-none">
            <div class="card shadow-sm h-100 hover-shadow">
                <div class="card-body text-center">
                    <i class="bi bi-cart-check-fill text-success" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Purchased Vehicles</h5>
                    <p class="text-muted small">Your completed purchases</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4 mb-3">
        <a href="{{ route('vehicles.index') }}" class="text-decoration-none">
            <div class="card shadow-sm h-100 hover-shadow">
                <div class="card-body text-center">
                    <i class="bi bi-car-front text-primary" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Browse Vehicles</h5>
                    <p class="text-muted small">Explore our inventory</p>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Messages Section -->
@if(isset($customer) && $customer->assignedAgent)
<div class="row mb-4">
    <div class="col-md-12 mb-3">
        <a href="{{ route('messages.index', $customer) }}" class="text-decoration-none">
            <div class="card shadow-sm hover-shadow border-primary">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-chat-dots-fill text-primary" style="font-size: 3rem;"></i>
                    <div class="ms-3">
                        <h5 class="mb-1">Messages with Your Agent</h5>
                        <p class="text-muted mb-0">Chat with {{ $customer->assignedAgent->name }}</p>
                    </div>
                    <i class="bi bi-arrow-right-circle ms-auto text-primary" style="font-size: 2rem;"></i>
                </div>
            </div>
        </a>
    </div>
</div>
@endif

<!-- My Invoices -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-receipt"></i> My Invoices</h5>
            </div>
            <div class="card-body">
                @if($invoices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Invoice#</th>
                                    <th>Vehicle</th>
                                    <th>Amount</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                <tr>
                                    <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                    <td>{{ $invoice->vehicle->title }}</td>
                                    <td>${{ number_format($invoice->vehicle_price, 2) }}</td>
                                    <td class="text-success">${{ number_format($invoice->total_paid, 2) }}</td>
                                    <td class="text-danger">${{ number_format($invoice->remaining_balance, 2) }}</td>
                                    <td>
                                        @if($invoice->status == 'Paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($invoice->status == 'Partial')
                                            <span class="badge bg-warning">Partial</span>
                                        @else
                                            <span class="badge bg-secondary">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> You don't have any invoices yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- My Assigned Agent -->
@if(isset($customer) && $customer->assignedAgent)
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-person-badge"></i> Your Sales Agent</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-person-circle" style="font-size: 3rem;"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">{{ $customer->assignedAgent->name }}</h5>
                        <p class="mb-1"><i class="bi bi-envelope"></i> {{ $customer->assignedAgent->email }}</p>
                        <p class="mb-0"><i class="bi bi-telephone"></i> {{ $customer->assignedAgent->phone }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
