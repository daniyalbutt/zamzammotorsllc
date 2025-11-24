@extends('layouts.app')

@section('title', 'My Account Info')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4"><i class="bi bi-info-circle"></i> My Account Information</h2>

    <div class="row">
        <!-- Account Overview -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-badge"></i> Account Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Full Name:</strong><br>
                        <span class="fs-5">{{ $user->name }}</span>
                    </div>

                    <div class="mb-3">
                        <strong>Email Address:</strong><br>
                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                    </div>

                    <div class="mb-3">
                        <strong>Phone Number:</strong><br>
                        {{ $customer->phone ?? 'Not provided' }}
                    </div>

                    <div class="mb-3">
                        <strong>Customer Status:</strong><br>
                        <span class="badge bg-{{ $customer->status === 'Active' ? 'success' : 'warning' }}">
                            {{ $customer->status }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <strong>Lead Source:</strong><br>
                        {{ $customer->lead_source ?? 'N/A' }}
                    </div>

                    <div class="mb-3">
                        <strong>Member Since:</strong><br>
                        {{ $customer->created_at->format('M d, Y') }}
                    </div>

                    <div class="mb-3">
                        <strong>Address:</strong><br>
                        {{ $customer->address ?? 'Not provided' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Assigned Agent -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill"></i> Your Sales Agent</h5>
                </div>
                <div class="card-body">
                    @if($customer->assignedAgent)
                        <div class="text-center mb-3">
                            <i class="bi bi-person-circle text-info" style="font-size: 5rem;"></i>
                        </div>

                        <div class="mb-3">
                            <strong>Agent Name:</strong><br>
                            <span class="fs-5">{{ $customer->assignedAgent->name }}</span>
                        </div>

                        <div class="mb-3">
                            <strong>Email:</strong><br>
                            <a href="mailto:{{ $customer->assignedAgent->email }}">
                                {{ $customer->assignedAgent->email }}
                            </a>
                        </div>

                        <div class="mb-3">
                            <strong>Phone:</strong><br>
                            {{ $customer->assignedAgent->phone ?? 'Not available' }}
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('messages.index', $customer) }}" class="btn btn-primary w-100">
                                <i class="bi bi-chat-dots"></i> Send Message
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-person-x" style="font-size: 4rem;"></i>
                            <p class="mt-3">No agent assigned yet</p>
                            <small>An agent will be assigned to assist you soon.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Order Statistics -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Order Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="bi bi-receipt text-primary" style="font-size: 2.5rem;"></i>
                                    <h3 class="mt-2">{{ $customer->invoices->count() }}</h3>
                                    <p class="text-muted mb-0">Total Orders</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="bi bi-hourglass-split text-warning" style="font-size: 2.5rem;"></i>
                                    <h3 class="mt-2">{{ $customer->invoices->where('status', 'Pending')->count() }}</h3>
                                    <p class="text-muted mb-0">Pending Orders</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="bi bi-check-circle text-success" style="font-size: 2.5rem;"></i>
                                    <h3 class="mt-2">{{ $customer->invoices->where('status', 'Paid')->count() }}</h3>
                                    <p class="text-muted mb-0">Completed Orders</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="bi bi-cash-stack text-info" style="font-size: 2.5rem;"></i>
                                    <h3 class="mt-2">${{ number_format($customer->invoices->sum('final_price'), 2) }}</h3>
                                    <p class="text-muted mb-0">Total Spent</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Orders</h5>
                </div>
                <div class="card-body">
                    @if($customer->invoices->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox" style="font-size: 4rem;"></i>
                            <p class="mt-3">No orders yet</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Vehicle</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->invoices->take(10) as $invoice)
                                        <tr>
                                            <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                            <td>{{ $invoice->vehicle->make ?? 'N/A' }} {{ $invoice->vehicle->model ?? '' }}</td>
                                            <td>{{ $invoice->created_at->format('M d, Y') }}</td>
                                            <td>${{ number_format($invoice->final_price, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $invoice->status === 'Paid' ? 'success' : ($invoice->status === 'Partially Paid' ? 'warning' : 'secondary') }}">
                                                    {{ $invoice->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
