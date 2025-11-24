@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-file-earmark-plus"></i> Create Invoice</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Invoices</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>
</div>

<form method="POST" action="{{ route('invoices.store') }}">
    @csrf
    
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Invoice Details</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Customer <span class="text-danger">*</span></label>
                    <select class="form-select @error('customer_id') is-invalid @enderror" 
                            name="customer_id" id="customer_id" required>
                        <option value="">Select Customer...</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', request('customer_id')) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->user->name }} ({{ $customer->user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Vehicle <span class="text-danger">*</span></label>
                    <select class="form-select @error('vehicle_id') is-invalid @enderror" 
                            name="vehicle_id" id="vehicle_id" required>
                        <option value="">Select Vehicle...</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" 
                                    data-price="{{ $vehicle->price }}"
                                    {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->title }} - ${{ number_format($vehicle->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Vehicle Price ($) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control @error('vehicle_price') is-invalid @enderror" 
                           name="vehicle_price" id="vehicle_price" value="{{ old('vehicle_price') }}" required readonly>
                    @error('vehicle_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Initial Payment ($)</label>
                    <input type="number" step="0.01" class="form-control @error('initial_payment') is-invalid @enderror" 
                           name="initial_payment" id="initial_payment" value="{{ old('initial_payment', 0) }}" min="0">
                    @error('initial_payment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Payment Method</label>
                    <select class="form-select @error('payment_method') is-invalid @enderror" name="payment_method">
                        <option value="">Select...</option>
                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="Cheque" {{ old('payment_method') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="Financing" {{ old('payment_method') == 'Financing' ? 'selected' : '' }}>Financing</option>
                        <option value="Card" {{ old('payment_method') == 'Card' ? 'selected' : '' }}>Card</option>
                        <option value="Other" {{ old('payment_method') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Payment Notes</label>
                    <input type="text" class="form-control @error('payment_notes') is-invalid @enderror" 
                           name="payment_notes" value="{{ old('payment_notes') }}">
                    @error('payment_notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              name="notes" rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Create Invoice
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.getElementById('vehicle_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    document.getElementById('vehicle_price').value = price || '';
});
</script>
@endpush
@endsection
