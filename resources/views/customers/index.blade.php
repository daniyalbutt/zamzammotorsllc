@extends('layouts.app')

@section('title', 'Customers')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="bi bi-people-fill"></i> Customers</h2>
    </div>
    <div class="col-md-6 text-end">
        @can('create customers')
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Add New Customer
            </a>
        @endcan
    </div>
</div>

<!-- Search & Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('customers.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Search by name, email, phone..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="Follow-up" {{ request('status') == 'Follow-up' ? 'selected' : '' }}>Follow-up</option>
                        <option value="In Negotiation" {{ request('status') == 'In Negotiation' ? 'selected' : '' }}>In Negotiation</option>
                        <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="lead_source">
                        <option value="">All Lead Sources</option>
                        <option value="Website" {{ request('lead_source') == 'Website' ? 'selected' : '' }}>Website</option>
                        <option value="WhatsApp" {{ request('lead_source') == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="Referral" {{ request('lead_source') == 'Referral' ? 'selected' : '' }}>Referral</option>
                        <option value="Walk-in" {{ request('lead_source') == 'Walk-in' ? 'selected' : '' }}>Walk-in</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Customers Table -->
<div class="card">
    <div class="card-body">
        @if($customers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Lead Source</th>
                            <th>Status</th>
                            <th>Assigned To</th>
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
                            <td>{{ $customer->user->email }}</td>
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
                            <td>{{ $customer->assignedAgent->name ?? 'Unassigned' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @can('edit customers')
                                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('delete customers')
                                        <form method="POST" action="{{ route('customers.destroy', $customer) }}" 
                                              onsubmit="return confirm('Delete this customer?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
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
                <i class="bi bi-info-circle"></i> No customers found.
            </div>
        @endif
    </div>
</div>
@endsection
