@extends('layouts.app')

@section('title', 'Add New Vehicle')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-plus-circle"></i> Add New Vehicle</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('vehicles.index') }}">Vehicles</a></li>
                <li class="breadcrumb-item active">Add New</li>
            </ol>
        </nav>
    </div>
</div>

<form method="POST" action="{{ route('vehicles.store') }}" enctype="multipart/form-data">
    @csrf
    
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Basic Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Make <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('make') is-invalid @enderror" 
                           name="make" value="{{ old('make') }}" required>
                    @error('make')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Model <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('model') is-invalid @enderror" 
                           name="model" value="{{ old('model') }}" required>
                    @error('model')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Year <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('year') is-invalid @enderror" 
                           name="year" value="{{ old('year') }}" min="1900" max="2100" required>
                    @error('year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Stock ID <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('stock_id') is-invalid @enderror" 
                           name="stock_id" value="{{ old('stock_id') }}" required>
                    @error('stock_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Condition <span class="text-danger">*</span></label>
                    <select class="form-select @error('condition') is-invalid @enderror" name="condition" required>
                        <option value="">Select...</option>
                        <option value="New" {{ old('condition') == 'New' ? 'selected' : '' }}>New</option>
                        <option value="Used" {{ old('condition') == 'Used' ? 'selected' : '' }}>Used</option>
                    </select>
                    @error('condition')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Availability <span class="text-danger">*</span></label>
                    <select class="form-select @error('availability') is-invalid @enderror" name="availability" required>
                        <option value="Available" {{ old('availability') == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Reserved" {{ old('availability') == 'Reserved' ? 'selected' : '' }}>Reserved</option>
                        <option value="Sold Out" {{ old('availability') == 'Sold Out' ? 'selected' : '' }}>Sold Out</option>
                    </select>
                    @error('availability')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Technical Specifications</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Steering Type <span class="text-danger">*</span></label>
                    <select class="form-select @error('steering_type') is-invalid @enderror" name="steering_type" required>
                        <option value="">Select...</option>
                        <option value="LHD" {{ old('steering_type') == 'LHD' ? 'selected' : '' }}>LHD (Left Hand Drive)</option>
                        <option value="RHD" {{ old('steering_type') == 'RHD' ? 'selected' : '' }}>RHD (Right Hand Drive)</option>
                    </select>
                    @error('steering_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Transmission <span class="text-danger">*</span></label>
                    <select class="form-select @error('transmission') is-invalid @enderror" name="transmission" required>
                        <option value="">Select...</option>
                        <option value="Automatic" {{ old('transmission') == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                        <option value="Manual" {{ old('transmission') == 'Manual' ? 'selected' : '' }}>Manual</option>
                        <option value="CVT" {{ old('transmission') == 'CVT' ? 'selected' : '' }}>CVT</option>
                        <option value="Semi Automatic" {{ old('transmission') == 'Semi Automatic' ? 'selected' : '' }}>Semi Automatic</option>
                    </select>
                    @error('transmission')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fuel Type <span class="text-danger">*</span></label>
                    <select class="form-select @error('fuel_type') is-invalid @enderror" name="fuel_type" required>
                        <option value="">Select...</option>
                        <option value="Gasoline" {{ old('fuel_type') == 'Gasoline' ? 'selected' : '' }}>Gasoline</option>
                        <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="Hybrid" {{ old('fuel_type') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                        <option value="Electric" {{ old('fuel_type') == 'Electric' ? 'selected' : '' }}>Electric</option>
                    </select>
                    @error('fuel_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Body Type <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('body_type') is-invalid @enderror" 
                           name="body_type" value="{{ old('body_type') }}" required>
                    @error('body_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Mileage (km) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('mileage') is-invalid @enderror" 
                           name="mileage" value="{{ old('mileage') }}" required>
                    @error('mileage')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Engine Capacity (cc)</label>
                    <input type="number" class="form-control @error('engine_capacity') is-invalid @enderror" 
                           name="engine_capacity" value="{{ old('engine_capacity') }}">
                    @error('engine_capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Color <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('color') is-invalid @enderror" 
                           name="color" value="{{ old('color') }}" required>
                    @error('color')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Number of Doors</label>
                    <input type="number" class="form-control @error('doors') is-invalid @enderror" 
                           name="doors" value="{{ old('doors') }}" min="2" max="6">
                    @error('doors')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Chassis/Engine Number</label>
                    <input type="text" class="form-control @error('chassis_engine_no') is-invalid @enderror" 
                           name="chassis_engine_no" value="{{ old('chassis_engine_no') }}">
                    @error('chassis_engine_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Price ($) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                           name="price" value="{{ old('price') }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Features</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Features</label>
                    <textarea class="form-control @error('features') is-invalid @enderror" 
                              name="features" rows="3">{{ old('features') }}</textarea>
                    <small class="text-muted">e.g., Bluetooth, Backup Camera, Cruise Control</small>
                    @error('features')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Safety Features</label>
                    <textarea class="form-control @error('safety_features') is-invalid @enderror" 
                              name="safety_features" rows="3">{{ old('safety_features') }}</textarea>
                    <small class="text-muted">e.g., ABS, Airbags, Traction Control</small>
                    @error('safety_features')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              name="description" rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Media</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Photos (Max 10, 5MB each)</label>
                    <input type="file" class="form-control @error('photos') is-invalid @enderror" 
                           name="photos[]" multiple accept="image/jpeg,image/png,image/jpg">
                    <small class="text-muted">Supported: JPEG, PNG, JPG</small>
                    @error('photos')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Video (Max 50MB)</label>
                    <input type="file" class="form-control @error('video') is-invalid @enderror" 
                           name="video" accept="video/mp4,video/mov,video/avi">
                    <small class="text-muted">Supported: MP4, MOV, AVI</small>
                    @error('video')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Create Vehicle
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
