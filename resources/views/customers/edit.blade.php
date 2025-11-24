@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-pencil"></i> Edit Customer</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customers.show', $customer) }}">{{ $customer->user->name }}</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<form method="POST" action="{{ route('customers.update', $customer) }}">
    @csrf
    @method('PUT')
    
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Customer Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           name="name" value="{{ old('name', $customer->user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email', $customer->user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                           name="phone" value="{{ old('phone', $customer->phone) }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Lead Source</label>
                    <select class="form-select @error('lead_source') is-invalid @enderror" name="lead_source" required>
                        <option value="Website" {{ old('lead_source', $customer->lead_source) == 'Website' ? 'selected' : '' }}>Website</option>
                        <option value="WhatsApp" {{ old('lead_source', $customer->lead_source) == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="Referral" {{ old('lead_source', $customer->lead_source) == 'Referral' ? 'selected' : '' }}>Referral</option>
                        <option value="Walk-in" {{ old('lead_source', $customer->lead_source) == 'Walk-in' ? 'selected' : '' }}>Walk-in</option>
                        <option value="Facebook" {{ old('lead_source', $customer->lead_source) == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                        <option value="Instagram" {{ old('lead_source', $customer->lead_source) == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                        <option value="Other" {{ old('lead_source', $customer->lead_source) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('lead_source')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                        <option value="Follow-up" {{ old('status', $customer->status) == 'Follow-up' ? 'selected' : '' }}>Follow-up</option>
                        <option value="In Negotiation" {{ old('status', $customer->status) == 'In Negotiation' ? 'selected' : '' }}>In Negotiation</option>
                        <option value="Closed" {{ old('status', $customer->status) == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Assign To Sales Agent</label>
                    <select class="form-select @error('assigned_to') is-invalid @enderror" name="assigned_to">
                        <option value="">Unassigned</option>
                        @foreach($sales_agents as $agent)
                            <option value="{{ $agent->id }}" {{ old('assigned_to', $customer->assigned_to) == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Address</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                              name="address" rows="2">{{ old('address', $customer->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <a href="{{ route('customers.show', $customer) }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Customer
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
