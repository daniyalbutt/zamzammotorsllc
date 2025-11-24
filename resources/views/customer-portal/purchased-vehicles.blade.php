@extends('layouts.app')

@section('title', 'Purchased Vehicles')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4"><i class="bi bi-cart-check-fill text-success"></i> My Purchased Vehicles</h2>

    @if($purchasedVehicles->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-cart-x" style="font-size: 5rem;" class="text-muted"></i>
                <h4 class="mt-4 text-muted">No Purchased Vehicles Yet</h4>
                <p class="text-muted">You haven't completed any vehicle purchases yet.</p>
                <a href="{{ route('vehicles.index') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-car-front"></i> Browse Vehicles
                </a>
            </div>
        </div>
    @else
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i>
            <strong>Congratulations!</strong> You have successfully purchased {{ $purchasedVehicles->count() }} vehicle(s).
        </div>

        <div class="row">
            @foreach($purchasedVehicles as $vehicle)
                <div class="col-md-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-check-circle"></i> Purchased: {{ $vehicle->make }} {{ $vehicle->model }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    @if($vehicle->photos->isNotEmpty())
                                        <img src="{{ Storage::url($vehicle->photos->first()->photo_path) }}"
                                             class="img-fluid rounded"
                                             alt="{{ $vehicle->make }} {{ $vehicle->model }}"
                                             style="height: 200px; width: 100%; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                             style="height: 200px;">
                                            <i class="bi bi-car-front" style="font-size: 4rem;" class="text-muted"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-5">
                                    <h4>{{ $vehicle->make }} {{ $vehicle->model }}</h4>

                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <small class="text-muted">Year:</small><br>
                                            <strong>{{ $vehicle->year }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Mileage:</small><br>
                                            <strong>{{ number_format($vehicle->mileage) }} {{ $vehicle->mileage_unit }}</strong>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <small class="text-muted">Transmission:</small><br>
                                            <strong>{{ $vehicle->transmission }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Fuel Type:</small><br>
                                            <strong>{{ $vehicle->fuel_type }}</strong>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <small class="text-muted">Color:</small><br>
                                            <strong>{{ $vehicle->exterior_color }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">VIN:</small><br>
                                            <strong>{{ $vehicle->vin }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    @php
                                        $invoice = $vehicle->invoices->where('customer_id', auth()->user()->customer->id)->first();
                                    @endphp

                                    @if($invoice)
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="text-muted mb-3">Purchase Details</h6>

                                                <div class="mb-2">
                                                    <small class="text-muted">Invoice Number:</small><br>
                                                    <strong>{{ $invoice->invoice_number }}</strong>
                                                </div>

                                                <div class="mb-2">
                                                    <small class="text-muted">Purchase Date:</small><br>
                                                    <strong>{{ $invoice->created_at->format('M d, Y') }}</strong>
                                                </div>

                                                <div class="mb-2">
                                                    <small class="text-muted">Purchase Price:</small><br>
                                                    <strong class="text-success fs-5">
                                                        ${{ number_format($invoice->final_price, 2) }}
                                                    </strong>
                                                </div>

                                                <div class="mb-3">
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Paid in Full
                                                    </span>
                                                </div>

                                                <div class="d-grid gap-2">
                                                    <a href="{{ route('invoices.show', $invoice) }}"
                                                       class="btn btn-sm btn-primary">
                                                        <i class="bi bi-receipt"></i> View Invoice
                                                    </a>

                                                    <a href="{{ route('vehicles.show', $vehicle) }}"
                                                       class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-eye"></i> Vehicle Details
                                                    </a>

                                                    @if($invoice->payments->isNotEmpty())
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-info"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#payments{{ $vehicle->id }}">
                                                            <i class="bi bi-cash-stack"></i> Payment History
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($invoice && $invoice->payments->isNotEmpty())
                                <div class="collapse mt-3" id="payments{{ $vehicle->id }}">
                                    <hr>
                                    <h6>Payment History</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Method</th>
                                                    <th>Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($invoice->payments as $payment)
                                                    <tr>
                                                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                                        <td>${{ number_format($payment->amount, 2) }}</td>
                                                        <td>{{ $payment->payment_method }}</td>
                                                        <td>{{ $payment->notes ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Summary Card -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm bg-light">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h3 class="text-primary">{{ $purchasedVehicles->count() }}</h3>
                                <p class="text-muted mb-0">Total Vehicles Purchased</p>
                            </div>
                            <div class="col-md-4">
                                <h3 class="text-success">
                                    ${{ number_format($purchasedVehicles->sum(function($v) {
                                        return $v->invoices->where('customer_id', auth()->user()->customer->id)->first()->final_price ?? 0;
                                    }), 2) }}
                                </h3>
                                <p class="text-muted mb-0">Total Investment</p>
                            </div>
                            <div class="col-md-4">
                                <h3 class="text-info">
                                    @if($purchasedVehicles->isNotEmpty())
                                        {{ $purchasedVehicles->first()->created_at->format('Y') }}
                                    @endif
                                </h3>
                                <p class="text-muted mb-0">First Purchase Year</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
