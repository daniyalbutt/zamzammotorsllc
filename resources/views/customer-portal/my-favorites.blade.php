@extends('layouts.app')

@section('title', 'My Favorites')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4"><i class="bi bi-heart-fill text-danger"></i> My Favorite Vehicles</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($favorites->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-heart" style="font-size: 5rem;" class="text-muted"></i>
                <h4 class="mt-4 text-muted">No Favorite Vehicles Yet</h4>
                <p class="text-muted">Browse our vehicle inventory and add your favorites!</p>
                <a href="{{ route('vehicles.index') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-car-front"></i> Browse Vehicles
                </a>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($favorites as $vehicle)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        @if($vehicle->photos->isNotEmpty())
                            <img src="{{ Storage::url($vehicle->photos->first()->photo_path) }}"
                                 class="card-img-top"
                                 alt="{{ $vehicle->make }} {{ $vehicle->model }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                 style="height: 200px;">
                                <i class="bi bi-car-front" style="font-size: 4rem;" class="text-muted"></i>
                            </div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">{{ $vehicle->make }} {{ $vehicle->model }}</h5>
                            <p class="text-muted mb-2">
                                <i class="bi bi-calendar"></i> {{ $vehicle->year }} |
                                <i class="bi bi-speedometer"></i> {{ number_format($vehicle->mileage) }} {{ $vehicle->mileage_unit }}
                            </p>

                            <div class="mb-3">
                                <span class="badge bg-{{ $vehicle->status === 'Available' ? 'success' : ($vehicle->status === 'Reserved' ? 'warning' : 'danger') }}">
                                    {{ $vehicle->status }}
                                </span>
                                <span class="badge bg-info">{{ $vehicle->condition }}</span>
                            </div>

                            <div class="mb-3">
                                <strong class="text-primary fs-5">
                                    ${{ number_format($vehicle->price, 2) }}
                                </strong>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i> View Details
                                </a>

                                <form action="{{ route('customer-portal.remove-from-favorites', $vehicle) }}"
                                      method="POST"
                                      onsubmit="return confirm('Remove this vehicle from favorites?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                        <i class="bi bi-heart-fill"></i> Remove from Favorites
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
