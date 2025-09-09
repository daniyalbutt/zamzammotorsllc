@extends('layouts.app')
@section('content')
    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Customers</li>
                </ol>
            </nav>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session()->get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session()->get('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Customers Management</h4>
                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Add New Customer
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-12">
            <div class="row">
                @forelse($data as $customer)
                    <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6 mb-4">
                        <div class="card__wrapper">
                            <div class="client__wrapper text-center">
                                <div class="client__thumb mb-15">
                                    <a href="{{ route('customers.show', $customer->id) }}">
                                        <img src="{{ $customer->image ? asset('storage/' . $customer->image) : asset('img/user.png') }}"
                                            alt="{{ $customer->name }}" class="img-fluid rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                                    </a>
                                </div>
                                <div class="client__content">
                                    <div class="client__meta">
                                        <h4 class="mb-8">
                                            <a href="{{ route('customers.show', $customer->id) }}">{{ $customer->name }}</a>
                                        </h4>
                                        <span>{{ $customer->email }}</span>
                                        @if($customer->getMeta('phone'))
                                            <p><i class="fas fa-phone me-1"></i> {{ $customer->getMeta('phone') }}</p>
                                        @endif
                                        @if($customer->assignedAgent)
                                            <p><i class="fas fa-user-tie me-1"></i> Assigned to: {{ $customer->assignedAgent->name }}</p>
                                        @else
                                            <p><i class="fas fa-exclamation-triangle me-1 text-warning"></i> No Agent Assigned</p>
                                        @endif
                                    </div>
                                    <div class="common-social mb-20">
                                        @if($customer->getMeta('facebook'))
                                            <a href="{{ $customer->getMeta('facebook') }}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                                        @endif
                                        @if($customer->getMeta('twitter'))
                                            <a href="{{ $customer->getMeta('twitter') }}" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                                        @endif
                                        @if($customer->getMeta('linkedin'))
                                            <a href="{{ $customer->getMeta('linkedin') }}" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
                                        @endif
                                        @if($customer->getMeta('youtube'))
                                            <a href="{{ $customer->getMeta('youtube') }}" target="_blank"><i class="fa-brands fa-youtube"></i></a>
                                        @endif
                                        @if($customer->getMeta('website'))
                                            <a href="{{ $customer->getMeta('website') }}" target="_blank"><i class="fa-thin fa-globe"></i></a>
                                        @endif
                                    </div>
                                    <div class="client__btn">
                                        <div class="d-flex align-items-center justify-content-center gap-15">
                                            @if($customer->getMeta('phone'))
                                                <a class="btn btn-outline-theme-border" href="tel:{{ $customer->getMeta('phone') }}">
                                                    <i class="fas fa-phone me-1"></i> Call
                                                </a>
                                            @endif
                                            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-outline-theme-border">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-outline-theme-border">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card__wrapper text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Customers Found</h5>
                                <p class="text-muted">Start by adding your first customer.</p>
                                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Add New Customer
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
@endsection
