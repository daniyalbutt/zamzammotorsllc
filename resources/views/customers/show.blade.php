@extends('layouts.app')
@section('content')
    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $customer->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Customer Profile Card -->
        <div class="col-xxl-4 col-xl-4 col-lg-5">
            <div class="card__wrapper">
                <div class="client__wrapper text-center">
                    <div class="client__thumb mb-20">
                        <img src="{{ $customer->image ? asset('storage/' . $customer->image) : asset('img/user.png') }}"
                            alt="{{ $customer->name }}" class="img-fluid rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <div class="client__content">
                        <div class="client__meta">
                            <h3 class="mb-10">{{ $customer->name }}</h3>
                            <p class="text-muted mb-15">{{ $customer->email }}</p>
                            
                            @if($customer->assignedAgent)
                                <div class="alert alert-info">
                                    <i class="fas fa-user-tie me-2"></i>
                                    <strong>Assigned Agent:</strong> {{ $customer->assignedAgent->name }}
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>No Agent Assigned</strong>
                                </div>
                            @endif
                        </div>

                        <!-- Contact Information -->
                        <div class="contact-info mb-20">
                            @if($customer->getMeta('phone'))
                                <div class="contact-item mb-10">
                                    <i class="fas fa-phone me-2 text-primary"></i>
                                    <a href="tel:{{ $customer->getMeta('phone') }}">{{ $customer->getMeta('phone') }}</a>
                                </div>
                            @endif
                            
                            @if($customer->getMeta('address'))
                                <div class="contact-item mb-10">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                    <span>{{ $customer->getMeta('address') }}</span>
                                </div>
                            @endif
                            
                            @if($customer->getMeta('birthday'))
                                <div class="contact-item mb-10">
                                    <i class="fas fa-birthday-cake me-2 text-primary"></i>
                                    <span>{{ \Carbon\Carbon::parse($customer->getMeta('birthday'))->format('M d, Y') }}</span>
                                </div>
                            @endif
                            
                            @if($customer->getMeta('gender'))
                                <div class="contact-item mb-10">
                                    <i class="fas fa-venus-mars me-2 text-primary"></i>
                                    <span>{{ ucfirst($customer->getMeta('gender')) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Social Media Links -->
                        <div class="common-social mb-20">
                            @if($customer->getMeta('facebook'))
                                <a href="{{ $customer->getMeta('facebook') }}" target="_blank" class="social-link">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </a>
                            @endif
                            @if($customer->getMeta('twitter'))
                                <a href="{{ $customer->getMeta('twitter') }}" target="_blank" class="social-link">
                                    <i class="fa-brands fa-x-twitter"></i>
                                </a>
                            @endif
                            @if($customer->getMeta('linkedin'))
                                <a href="{{ $customer->getMeta('linkedin') }}" target="_blank" class="social-link">
                                    <i class="fa-brands fa-linkedin-in"></i>
                                </a>
                            @endif
                            @if($customer->getMeta('youtube'))
                                <a href="{{ $customer->getMeta('youtube') }}" target="_blank" class="social-link">
                                    <i class="fa-brands fa-youtube"></i>
                                </a>
                            @endif
                            @if($customer->getMeta('instagram'))
                                <a href="{{ $customer->getMeta('instagram') }}" target="_blank" class="social-link">
                                    <i class="fa-brands fa-instagram"></i>
                                </a>
                            @endif
                            @if($customer->getMeta('website'))
                                <a href="{{ $customer->getMeta('website') }}" target="_blank" class="social-link">
                                    <i class="fa-thin fa-globe"></i>
                                </a>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="client__btn">
                            <div class="d-flex flex-column gap-10">
                                @if($customer->getMeta('phone'))
                                    <a class="btn btn-primary" href="tel:{{ $customer->getMeta('phone') }}">
                                        <i class="fas fa-phone me-2"></i> Call Customer
                                    </a>
                                @endif
                                @can('add or edit customer')
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-edit me-2"></i> Edit Customer
                                    </a>
                                @endcan
                                <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Back to Customers
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Details -->
        <div class="col-xxl-8 col-xl-8 col-lg-7">
            <div class="card__wrapper">
                <div class="card__title-wrap d-flex align-items-center justify-content-between mb-20">
                    <h5 class="card__heading-title">Customer Information</h5>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <p class="form-control-plaintext">{{ $customer->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email Address</label>
                        <p class="form-control-plaintext">
                            <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Phone Number</label>
                        <p class="form-control-plaintext">
                            @if($customer->getMeta('phone'))
                                <a href="tel:{{ $customer->getMeta('phone') }}">{{ $customer->getMeta('phone') }}</a>
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Gender</label>
                        <p class="form-control-plaintext">
                            @if($customer->getMeta('gender'))
                                {{ ucfirst($customer->getMeta('gender')) }}
                            @else
                                <span class="text-muted">Not specified</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Birthday</label>
                        <p class="form-control-plaintext">
                            @if($customer->getMeta('birthday'))
                                {{ \Carbon\Carbon::parse($customer->getMeta('birthday'))->format('F d, Y') }}
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Assigned Agent</label>
                        <p class="form-control-plaintext">
                            @if($customer->assignedAgent)
                                <a href="{{ route('employees.show', $customer->assignedAgent->id) }}">
                                    {{ $customer->assignedAgent->name }}
                                </a>
                            @else
                                <span class="text-muted">No agent assigned</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <p class="form-control-plaintext">
                            @if($customer->getMeta('address'))
                                {{ $customer->getMeta('address') }}
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Social Media Links Details -->
            <div class="card__wrapper mt-20">
                <div class="card__title-wrap d-flex align-items-center justify-content-between mb-20">
                    <h5 class="card__heading-title">Social Media & Web Presence</h5>
                </div>

                <div class="row">
                    @php
                        $socialLinks = [
                            'facebook' => ['icon' => 'fa-brands fa-facebook-f', 'label' => 'Facebook'],
                            'twitter' => ['icon' => 'fa-brands fa-x-twitter', 'label' => 'Twitter'],
                            'linkedin' => ['icon' => 'fa-brands fa-linkedin-in', 'label' => 'LinkedIn'],
                            'youtube' => ['icon' => 'fa-brands fa-youtube', 'label' => 'YouTube'],
                            'instagram' => ['icon' => 'fa-brands fa-instagram', 'label' => 'Instagram'],
                            'website' => ['icon' => 'fa-thin fa-globe', 'label' => 'Website']
                        ];
                    @endphp

                    @foreach($socialLinks as $key => $social)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ $social['label'] }}</label>
                            <p class="form-control-plaintext">
                                @if($customer->getMeta($key))
                                    <a href="{{ $customer->getMeta($key) }}" target="_blank" class="text-decoration-none">
                                        <i class="{{ $social['icon'] }} me-2"></i>
                                        {{ $customer->getMeta($key) }}
                                    </a>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Account Information -->
            <div class="card__wrapper mt-20">
                <div class="card__title-wrap d-flex align-items-center justify-content-between mb-20">
                    <h5 class="card__heading-title">Account Information</h5>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Account Created</label>
                        <p class="form-control-plaintext">{{ $customer->created_at->format('F d, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Last Updated</label>
                        <p class="form-control-plaintext">{{ $customer->updated_at->format('F d, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Created By</label>
                        <p class="form-control-plaintext">
                            @if($customer->createdByUser)
                                {{ $customer->createdByUser->name }}
                            @else
                                <span class="text-muted">System</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Account Status</label>
                        <p class="form-control-plaintext">
                            <span class="badge bg-success">Active</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('css')
    <style>
        .social-link {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            border-radius: 50%;
            background-color: #f8f9fa;
            color: #6c757d;
            text-decoration: none;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background-color: #007bff;
            color: white;
            transform: translateY(-2px);
        }

        .contact-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
        }

        .form-control-plaintext {
            padding: 0.375rem 0;
            margin-bottom: 0;
            line-height: 1.5;
            color: #212529;
            background-color: transparent;
            border: solid transparent;
            border-width: 1px 0;
        }

        .card__wrapper {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .client__wrapper {
            padding: 20px;
        }

        .client__thumb img {
            border: 4px solid #f8f9fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush
