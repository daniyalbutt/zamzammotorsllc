@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="bi bi-receipt-cutoff"></i> Invoices</h2>
    </div>
    <div class="col-md-6 text-end">
        @can('create invoices')
            <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create Invoice
            </a>
        @endcan
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('invoices.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Search invoice number, customer..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="Unpaid" {{ request('status') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="Partial" {{ request('status') == 'Partial' ? 'selected' : '' }}>Partial</option>
                        <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Invoices Table -->
<div class="card">
    <div class="card-body">
        @if($invoices->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Invoice#</th>
                            <th>Customer</th>
                            <th>Vehicle</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                        <tr>
                            <td><strong>{{ $invoice->invoice_number }}</strong></td>
                            <td>{{ $invoice->customer->user->name }}</td>
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
                            <td>{{ $invoice->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('invoices.download', $invoice) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="3" class="text-end">Totals:</td>
                            <td>${{ number_format($invoices->sum('vehicle_price'), 2) }}</td>
                            <td class="text-success">${{ number_format($invoices->sum('total_paid'), 2) }}</td>
                            <td class="text-danger">${{ number_format($invoices->sum('remaining_balance'), 2) }}</td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="mt-3">
                {{ $invoices->links() }}
            </div>
        @else
            <div class="alert alert-info mb-0">
                <i class="bi bi-info-circle"></i> No invoices found.
            </div>
        @endif
    </div>
</div>
@endsection
