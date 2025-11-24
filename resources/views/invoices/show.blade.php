@extends('layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number)

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="bi bi-receipt"></i> Invoice {{ $invoice->invoice_number }}</h2>
        <p class="text-muted">Created on {{ $invoice->created_at->format('M d, Y') }}</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('invoices.download', $invoice) }}" class="btn btn-info">
            <i class="bi bi-download"></i> Download PDF
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Invoice Details -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Invoice Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <p class="mb-0">
                            <strong>Name:</strong> {{ $invoice->customer->user->name }}<br>
                            <strong>Email:</strong> {{ $invoice->customer->user->email }}<br>
                            <strong>Phone:</strong> {{ $invoice->customer->phone }}<br>
                            @if($invoice->customer->address)
                                <strong>Address:</strong> {{ $invoice->customer->address }}
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Vehicle Information</h6>
                        <p class="mb-0">
                            <strong>Vehicle:</strong> {{ $invoice->vehicle->title }}<br>
                            <strong>Year:</strong> {{ $invoice->vehicle->year }}<br>
                            <strong>Stock ID:</strong> {{ $invoice->vehicle->stock_id }}<br>
                            <strong>VIN/Chassis:</strong> {{ $invoice->vehicle->chassis_engine_no ?? 'N/A' }}
                        </p>
                    </div>
                </div>

                <hr>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td><strong>Vehicle Price</strong></td>
                                <td class="text-end">${{ number_format($invoice->vehicle_price, 2) }}</td>
                            </tr>
                            <tr class="table-success">
                                <td><strong>Total Paid</strong></td>
                                <td class="text-end text-success fw-bold">${{ number_format($invoice->total_paid, 2) }}</td>
                            </tr>
                            <tr class="table-danger">
                                <td><strong>Remaining Balance</strong></td>
                                <td class="text-end text-danger fw-bold">${{ number_format($invoice->remaining_balance, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td class="text-end">
                                    @if($invoice->status == 'Paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($invoice->status == 'Partial')
                                        <span class="badge bg-warning">Partial</span>
                                    @else
                                        <span class="badge bg-secondary">Unpaid</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if($invoice->notes)
                    <div class="alert alert-info mb-0">
                        <strong>Notes:</strong> {{ $invoice->notes }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Payment History -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Payment History</h5>
            </div>
            <div class="card-body">
                @if($invoice->payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Notes</th>
                                    <th>Received By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td class="text-success fw-bold">${{ number_format($payment->amount, 2) }}</td>
                                    <td><span class="badge bg-secondary">{{ $payment->payment_method }}</span></td>
                                    <td>{{ $payment->notes ?? '-' }}</td>
                                    <td>{{ $payment->recorder ? $payment->recorder->name : 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td>Total Paid:</td>
                                    <td class="text-success" colspan="4">${{ number_format($invoice->payments->sum('amount'), 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No payments recorded yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Add Payment -->
        @if($invoice->remaining_balance > 0 && auth()->user()->can('create invoices'))
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-cash-coin"></i> Add Payment</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('invoices.add-payment', $invoice) }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Amount ($) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                               name="amount" max="{{ $invoice->remaining_balance }}" required>
                        <small class="text-muted">Max: ${{ number_format($invoice->remaining_balance, 2) }}</small>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select class="form-select @error('payment_method') is-invalid @enderror" name="payment_method" required>
                            <option value="">Select...</option>
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Financing">Financing</option>
                            <option value="Card">Card</option>
                            <option value="Other">Other</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                               name="payment_date" value="{{ date('Y-m-d') }}" required>
                        @error('payment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  name="notes" rows="2"></textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-plus-circle"></i> Add Payment
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Quick Info -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Quick Info</h5>
            </div>
            <div class="card-body">
                <dl class="mb-0">
                    <dt>Created By:</dt>
                    <dd>{{ $invoice->creator ? $invoice->creator->name : 'N/A' }}</dd>

                    <dt>Created Date:</dt>
                    <dd>{{ $invoice->created_at->format('M d, Y g:i A') }}</dd>

                    <dt>Last Updated:</dt>
                    <dd>{{ $invoice->updated_at->diffForHumans() }}</dd>

                    <dt>Vehicle Status:</dt>
                    <dd class="mb-0">
                        @if($invoice->vehicle->availability == 'Available')
                            <span class="badge bg-success">Available</span>
                        @elseif($invoice->vehicle->availability == 'Reserved')
                            <span class="badge bg-warning">Reserved</span>
                        @else
                            <span class="badge bg-danger">Sold Out</span>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
