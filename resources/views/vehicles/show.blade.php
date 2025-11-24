@extends('layouts.app')

@section('title', $vehicle->title)

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2>{{ $vehicle->title }}</h2>
        <p class="text-muted">Stock ID: {{ $vehicle->stock_id }}</p>
    </div>
    <div class="col-md-4 text-end">
        @can('edit vehicles')
            <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
        @endcan
        @can('delete vehicles')
            <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}" 
                  onsubmit="return confirm('Are you sure?');" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </form>
        @endcan
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Photos Gallery -->
        @if($vehicle->photos->count() > 0)
            <div class="card mb-4">
                <div class="card-body">
                    <div id="vehicleCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($vehicle->photos as $index => $photo)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ Storage::url($photo->photo_path) }}" 
                                         class="d-block w-100" alt="Vehicle photo" 
                                         style="max-height: 500px; object-fit: contain;">
                                </div>
                            @endforeach
                        </div>
                        @if($vehicle->photos->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#vehicleCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#vehicleCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Video -->
        @if($vehicle->video_path)
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-play-circle"></i> Video</h5>
                </div>
                <div class="card-body">
                    <video controls class="w-100">
                        <source src="{{ Storage::url($vehicle->video_path) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        @endif

        <!-- Description -->
        @if($vehicle->description)
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Description</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $vehicle->description }}</p>
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Status & Price -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Status & Pricing</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>Status:</strong>
                    @if($vehicle->availability == 'Available')
                        <span class="badge bg-success">Available</span>
                    @elseif($vehicle->availability == 'Reserved')
                        <span class="badge bg-warning">Reserved</span>
                    @else
                        <span class="badge bg-danger">Sold Out</span>
                    @endif
                </p>
                @if(auth()->user()->hasAnyRole(['Super Admin', 'Sales Manager', 'Sales Agent']))
                    <p class="mb-0">
                        <strong>Price:</strong> 
                        <span class="h4 text-success">${{ number_format($vehicle->price, 2) }}</span>
                    </p>
                @endif
            </div>
        </div>

        <!-- Specifications -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Specifications</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-6">Make:</dt>
                    <dd class="col-sm-6">{{ $vehicle->make }}</dd>

                    <dt class="col-sm-6">Model:</dt>
                    <dd class="col-sm-6">{{ $vehicle->model }}</dd>

                    <dt class="col-sm-6">Year:</dt>
                    <dd class="col-sm-6">{{ $vehicle->year }}</dd>

                    <dt class="col-sm-6">Condition:</dt>
                    <dd class="col-sm-6">{{ $vehicle->condition }}</dd>

                    <dt class="col-sm-6">Transmission:</dt>
                    <dd class="col-sm-6">{{ $vehicle->transmission }}</dd>

                    <dt class="col-sm-6">Fuel Type:</dt>
                    <dd class="col-sm-6">{{ $vehicle->fuel_type }}</dd>

                    <dt class="col-sm-6">Mileage:</dt>
                    <dd class="col-sm-6">{{ number_format($vehicle->mileage) }} km</dd>

                    <dt class="col-sm-6">Color:</dt>
                    <dd class="col-sm-6">{{ $vehicle->color }}</dd>

                    @if($vehicle->engine_capacity)
                        <dt class="col-sm-6">Engine:</dt>
                        <dd class="col-sm-6">{{ $vehicle->engine_capacity }} cc</dd>
                    @endif

                    @if($vehicle->doors)
                        <dt class="col-sm-6">Doors:</dt>
                        <dd class="col-sm-6">{{ $vehicle->doors }}</dd>
                    @endif

                    <dt class="col-sm-6">Steering:</dt>
                    <dd class="col-sm-6 mb-0">{{ $vehicle->steering_type }}</dd>
                </dl>
            </div>
        </div>

        <!-- Features -->
        @if($vehicle->features || $vehicle->safety_features)
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Features</h5>
                </div>
                <div class="card-body">
                    @if($vehicle->features)
                        <p class="mb-2"><strong>Standard Features:</strong></p>
                        <p class="small">{{ $vehicle->features }}</p>
                    @endif
                    @if($vehicle->safety_features)
                        <p class="mb-2"><strong>Safety Features:</strong></p>
                        <p class="small mb-0">{{ $vehicle->safety_features }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
