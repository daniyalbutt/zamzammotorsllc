@extends('layouts.app')

@section('title', 'Sales Agent Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-person-badge"></i> Sales Agent Dashboard</h2>
        <p class="text-muted">Welcome, {{ auth()->user()->name }}! Track your assigned customers and sales.</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Assigned Customers</h6>
                        <h3 class="card-title mb-0">{{ $stats['assigned_customers'] }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
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
                        <h6 class="card-subtitle mb-2 text-muted">Sales Closed</h6>
                        <h3 class="card-title mb-0">{{ $stats['closed_sales'] }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-check2-circle" style="font-size: 2rem;"></i>
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
                        <h6 class="card-subtitle mb-2 text-muted">In Negotiation</h6>
                        <h3 class="card-title mb-0">{{ $stats['in_negotiation'] }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-chat-dots-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
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
</div>

<!-- Customer Lookup Widget -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="bi bi-search"></i> Check Customer Availability</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    <i class="bi bi-info-circle"></i>
                    Before creating a new customer, check if they're already registered or assigned to another agent.
                </p>

                <form id="customerLookupForm" class="row g-3">
                    @csrf
                    <div class="col-md-9">
                        <input type="text"
                               id="customerSearchInput"
                               class="form-control form-control-lg"
                               placeholder="Enter phone number, email, or customer name..."
                               minlength="3"
                               required>
                        <small class="text-muted">Minimum 3 characters</small>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-danger btn-lg w-100" id="searchBtn">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </form>

                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="text-center mt-3" style="display: none;">
                    <div class="spinner-border text-danger" role="status">
                        <span class="visually-hidden">Searching...</span>
                    </div>
                    <p class="text-muted mt-2">Searching...</p>
                </div>

                <!-- Results Container -->
                <div id="searchResults" class="mt-4"></div>
            </div>
        </div>
    </div>
</div>

<!-- My Customers -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-people"></i> My Customers</h5>
            </div>
            <div class="card-body">
                @if($customers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Lead Source</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                <tr>
                                    <td>
                                        <i class="bi bi-person-circle"></i>
                                        {{ $customer->user->name }}
                                    </td>
                                    <td>{{ $customer->phone }}</td>
                                    <td><span class="badge bg-secondary">{{ $customer->lead_source }}</span></td>
                                    <td>
                                        @if($customer->status == 'Follow-up')
                                            <span class="badge bg-warning">Follow-up</span>
                                        @elseif($customer->status == 'In Negotiation')
                                            <span class="badge bg-info">In Negotiation</span>
                                        @else
                                            <span class="badge bg-success">Closed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $customers->links() }}
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> No customers assigned to you yet.
                    </div>
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
                    <div class="col-md-4">
                        <a href="{{ route('vehicles.index') }}" class="btn btn-primary w-100">
                            <i class="bi bi-car-front"></i> View Vehicles
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('invoices.create') }}" class="btn btn-warning w-100">
                            <i class="bi bi-file-earmark-plus"></i> Create Invoice
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('customers.index') }}" class="btn btn-success w-100">
                            <i class="bi bi-people"></i> My Customers
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('customerLookupForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const searchInput = document.getElementById('customerSearchInput');
    const searchValue = searchInput.value.trim();

    if (searchValue.length < 3) {
        alert('Please enter at least 3 characters');
        return;
    }

    const loadingSpinner = document.getElementById('loadingSpinner');
    const searchResults = document.getElementById('searchResults');
    const searchBtn = document.getElementById('searchBtn');

    // Show loading
    loadingSpinner.style.display = 'block';
    searchResults.innerHTML = '';
    searchBtn.disabled = true;

    // Make AJAX request
    fetch('{{ route("customers.check-customer") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({
            search: searchValue
        })
    })
    .then(response => response.json())
    .then(data => {
        loadingSpinner.style.display = 'none';
        searchBtn.disabled = false;

        if (!data.found) {
            // No customer found
            searchResults.innerHTML = `
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    <strong>Good News!</strong> ${data.message}
                    <br><small class="text-muted">Please contact your Sales Manager to create a new customer record.</small>
                </div>
            `;
        } else {
            // Customer(s) found
            let resultsHTML = `
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Customer(s) Found!</strong> ${data.customers.length} matching customer(s) found in the system.
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Lead Source</th>
                                <th>Assigned Agent</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            data.customers.forEach(customer => {
                const agentBadge = customer.assigned_agent
                    ? `<span class="badge ${customer.is_assigned_to_me ? 'bg-success' : 'bg-info'}">
                         ${customer.assigned_agent.name}
                       </span>`
                    : '<span class="badge bg-secondary">Unassigned</span>';

                const statusClass = customer.status === 'Closed' ? 'success' :
                                   (customer.status === 'In Negotiation' ? 'info' : 'warning');

                const warningMessage = customer.is_assigned_to_me
                    ? '<small class="text-success"><i class="bi bi-check-circle"></i> This is your customer</small>'
                    : customer.assigned_agent
                        ? `<small class="text-danger"><i class="bi bi-exclamation-circle"></i> Already assigned to ${customer.assigned_agent.name}</small>`
                        : '<small class="text-muted">Not assigned yet</small>';

                resultsHTML += `
                    <tr ${customer.is_assigned_to_me ? 'class="table-success"' : (customer.assigned_agent ? 'class="table-warning"' : '')}>
                        <td><strong>${customer.name}</strong></td>
                        <td>${customer.email}</td>
                        <td>${customer.phone || 'N/A'}</td>
                        <td><span class="badge bg-${statusClass}">${customer.status}</span></td>
                        <td><span class="badge bg-secondary">${customer.lead_source}</span></td>
                        <td>
                            ${agentBadge}<br>
                            ${warningMessage}
                        </td>
                        <td>${customer.created_at}</td>
                        <td>
                            <a href="/customers/${customer.id}" class="btn btn-sm btn-primary" target="_blank">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                `;
            });

            resultsHTML += `
                        </tbody>
                    </table>
                </div>
            `;

            searchResults.innerHTML = resultsHTML;
        }
    })
    .catch(error => {
        loadingSpinner.style.display = 'none';
        searchBtn.disabled = false;
        searchResults.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-x-circle"></i>
                <strong>Error!</strong> Something went wrong. Please try again.
            </div>
        `;
        console.error('Error:', error);
    });
});
</script>
@endpush
