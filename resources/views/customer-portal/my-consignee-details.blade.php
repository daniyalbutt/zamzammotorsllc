@extends('layouts.app')

@section('title', 'My Consignee Details')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4"><i class="bi bi-truck"></i> My Consignee Details</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill"></i> Consignee Information</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>What is a Consignee?</strong><br>
                        A consignee is the person or entity who will receive the vehicle on your behalf. This could be yourself or someone else you designate.
                    </div>

                    <form action="{{ route('customer-portal.update-consignee-details') }}" method="POST">
                        @csrf

                        @php
                            $consignee = $customer->consignee_details ?? [];
                        @endphp

                        <div class="mb-3">
                            <label for="consignee_name" class="form-label">
                                Consignee Full Name
                            </label>
                            <input type="text"
                                   name="consignee_name"
                                   id="consignee_name"
                                   class="form-control @error('consignee_name') is-invalid @enderror"
                                   value="{{ old('consignee_name', $consignee['consignee_name'] ?? '') }}"
                                   placeholder="Enter full name">
                            @error('consignee_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="consignee_phone" class="form-label">
                                    Phone Number
                                </label>
                                <input type="text"
                                       name="consignee_phone"
                                       id="consignee_phone"
                                       class="form-control @error('consignee_phone') is-invalid @enderror"
                                       value="{{ old('consignee_phone', $consignee['consignee_phone'] ?? '') }}"
                                       placeholder="+1 234 567 8900">
                                @error('consignee_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="consignee_email" class="form-label">
                                    Email Address
                                </label>
                                <input type="email"
                                       name="consignee_email"
                                       id="consignee_email"
                                       class="form-control @error('consignee_email') is-invalid @enderror"
                                       value="{{ old('consignee_email', $consignee['consignee_email'] ?? '') }}"
                                       placeholder="email@example.com">
                                @error('consignee_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="consignee_address" class="form-label">
                                Street Address
                            </label>
                            <textarea name="consignee_address"
                                      id="consignee_address"
                                      class="form-control @error('consignee_address') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Enter full street address">{{ old('consignee_address', $consignee['consignee_address'] ?? '') }}</textarea>
                            @error('consignee_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="consignee_city" class="form-label">
                                    City
                                </label>
                                <input type="text"
                                       name="consignee_city"
                                       id="consignee_city"
                                       class="form-control @error('consignee_city') is-invalid @enderror"
                                       value="{{ old('consignee_city', $consignee['consignee_city'] ?? '') }}"
                                       placeholder="City">
                                @error('consignee_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="consignee_country" class="form-label">
                                    Country
                                </label>
                                <input type="text"
                                       name="consignee_country"
                                       id="consignee_country"
                                       class="form-control @error('consignee_country') is-invalid @enderror"
                                       value="{{ old('consignee_country', $consignee['consignee_country'] ?? '') }}"
                                       placeholder="Country">
                                @error('consignee_country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="consignee_postal_code" class="form-label">
                                    Postal/ZIP Code
                                </label>
                                <input type="text"
                                       name="consignee_postal_code"
                                       id="consignee_postal_code"
                                       class="form-control @error('consignee_postal_code') is-invalid @enderror"
                                       value="{{ old('consignee_postal_code', $consignee['consignee_postal_code'] ?? '') }}"
                                       placeholder="12345">
                                @error('consignee_postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Dashboard
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Consignee Details
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
