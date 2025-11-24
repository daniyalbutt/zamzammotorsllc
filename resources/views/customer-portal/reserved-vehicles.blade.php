@extends('layouts.app')

@section('title', 'Reserved Vehicles')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4"><i class="bi bi-bookmark-fill text-warning"></i> My Reserved Vehicles</h2>

    @if($reservedVehicles->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-bookmark" style="font-size: 5rem;" class="text-muted"></i>
                <h4 class="mt-4 text-muted">No Reserved Vehicles</h4>
                <p class="text-muted">You don't have any vehicles on reserve at the moment.</p>
                <a href="{{ route('vehicles.index') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-car-front"></i> Browse Available Vehicles
                </a>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            <strong>Reserved Vehicles:</strong> These vehicles are on hold for you. Please complete the payment to finalize your purchase.
        </div>

        <div class="row">
            @foreach($reservedVehicles as $vehicle)
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="row g-0">
                            <div class="col-md-5">
                                @if($vehicle->photos->isNotEmpty())
                                    <img src="{{ Storage::url($vehicle->photos->first()->photo_path) }}"
                                         class="img-fluid rounded-start h-100"
                                         alt="{{ $vehicle->make }} {{ $vehicle->model }}"
                                         style="object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                        <i class="bi bi-car-front" style="font-size: 4rem;" class="text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-7">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="card-title">{{ $vehicle->make }} {{ $vehicle->model }}</h5>
                                        <span class="badge bg-warning">Reserved</span>
                                    </div>

                                    <p class="text-muted mb-2">
                                        <i class="bi bi-calendar"></i> {{ $vehicle->year }}<br>
                                        <i class="bi bi-speedometer"></i> {{ number_format($vehicle->mileage) }} {{ $vehicle->mileage_unit }}<br>
                                        <i class="bi bi-gear"></i> {{ $vehicle->transmission }}<br>
                                        <i class="bi bi-fuel-pump"></i> {{ $vehicle->fuel_type }}
                                    </p>

                                    <div class="mb-3">
                                        <strong class="text-primary fs-5">
                                            ${{ number_format($vehicle->price, 2) }}
                                        </strong>
                                    </div>

                                    @php
                                        $invoice = $vehicle->invoices->where('customer_id', auth()->user()->customer->id)->first();
                                    @endphp

                                    @if($invoice)
                                        <div class="alert alert-warning py-2 mb-2">
                                            <small>
                                                <strong>Invoice:</strong> #{{ $invoice->invoice_number }}<br>
                                                <strong>Status:</strong> {{ $invoice->status }}<br>
                                                <strong>Amount Due:</strong> ${{ number_format($invoice->final_price - $invoice->payments->sum('amount'), 2) }}
                                            </small>
                                        </div>

                                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-primary btn-sm w-100">
                                            <i class="bi bi-receipt"></i> View Invoice & Make Payment
                                        </a>
                                    @endif

                                    <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                                        <i class="bi bi-eye"></i> View Vehicle Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
