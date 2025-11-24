@extends('layouts.app')

@section('title', 'Vehicles')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="bi bi-car-front-fill"></i> Vehicles</h2>
    </div>
    <div class="col-md-6 text-end">
        @can('create vehicles')
            <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Vehicle
            </a>
        @endcan
    </div>
</div>

<!-- Search & Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('vehicles.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="make">
                        <option value="">All Makes</option>
                        <option value="Toyota" {{ request('make') == 'Toyota' ? 'selected' : '' }}>Toyota</option>
                        <option value="Honda" {{ request('make') == 'Honda' ? 'selected' : '' }}>Honda</option>
                        <option value="Ford" {{ request('make') == 'Ford' ? 'selected' : '' }}>Ford</option>
                        <option value="BMW" {{ request('make') == 'BMW' ? 'selected' : '' }}>BMW</option>
                        <option value="Mercedes-Benz" {{ request('make') == 'Mercedes-Benz' ? 'selected' : '' }}>Mercedes-Benz</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="condition">
                        <option value="">All Conditions</option>
                        <option value="New" {{ request('condition') == 'New' ? 'selected' : '' }}>New</option>
                        <option value="Used" {{ request('condition') == 'Used' ? 'selected' : '' }}>Used</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="availability">
                        <option value="">All Status</option>
                        <option value="Available" {{ request('availability') == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Reserved" {{ request('availability') == 'Reserved' ? 'selected' : '' }}>Reserved</option>
                        <option value="Sold Out" {{ request('availability') == 'Sold Out' ? 'selected' : '' }}>Sold Out</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Vehicles Grid -->
<div class="row g-4">
    @forelse($vehicles as $vehicle)
        <div class="col-md-4">
            <div class="card h-100">
                @if($vehicle->photos->count() > 0)
                    <img src="{{ Storage::url($vehicle->photos->first()->photo_path) }}" 
                         class="card-img-top" alt="{{ $vehicle->title }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-car-front-fill" style="font-size: 4rem;"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $vehicle->title }}</h5>
                    <p class="card-text">
                        <small class="text-muted">
                            <i class="bi bi-calendar"></i> {{ $vehicle->year }} |
                            <i class="bi bi-speedometer"></i> {{ number_format($vehicle->mileage) }} km
                        </small>
                    </p>
                    <div class="mb-2">
                        <span class="badge bg-secondary">{{ $vehicle->condition }}</span>
                        @if($vehicle->availability == 'Available')
                            <span class="badge bg-success">Available</span>
                        @elseif($vehicle->availability == 'Reserved')
                            <span class="badge bg-warning">Reserved</span>
                        @else
                            <span class="badge bg-danger">Sold Out</span>
                        @endif
                    </div>
                    @if(auth()->user()->hasAnyRole(['Super Admin', 'Sales Manager']))
                        <p class="mb-2"><strong>Price:</strong> ${{ number_format($vehicle->price, 2) }}</p>
                    @endif
                    <p class="mb-2"><strong>Stock ID:</strong> {{ $vehicle->stock_id }}</p>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> View
                        </a>
                        @can('edit vehicles')
                            <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        @endcan
                        @can('delete vehicles')
                            <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}" 
                                  onsubmit="return confirm('Are you sure you want to delete this vehicle?');" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No vehicles found.
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $vehicles->links() }}
</div>
@endsection
