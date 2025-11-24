@extends('layouts.app')

@section('title', 'Edit Vehicle')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-pencil"></i> Edit Vehicle</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('vehicles.index') }}">Vehicles</a></li>
                <li class="breadcrumb-item"><a href="{{ route('vehicles.show', $vehicle) }}">{{ $vehicle->title }}</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<form method="POST" action="{{ route('vehicles.update', $vehicle) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Basic Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           name="title" value="{{ old('title', $vehicle->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Make <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('make') is-invalid @enderror" 
                           name="make" value="{{ old('make', $vehicle->make) }}" required>
                    @error('make')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Model <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('model') is-invalid @enderror" 
                           name="model" value="{{ old('model', $vehicle->model) }}" required>
                    @error('model')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Year <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('year') is-invalid @enderror" 
                           name="year" value="{{ old('year', $vehicle->year) }}" min="1900" max="2100" required>
                    @error('year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Stock ID <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('stock_id') is-invalid @enderror" 
                           name="stock_id" value="{{ old('stock_id', $vehicle->stock_id) }}" required>
                    @error('stock_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Condition <span class="text-danger">*</span></label>
                    <select class="form-select @error('condition') is-invalid @enderror" name="condition" required>
                        <option value="">Select...</option>
                        <option value="New" {{ old('condition', $vehicle->condition) == 'New' ? 'selected' : '' }}>New</option>
                        <option value="Used" {{ old('condition', $vehicle->condition) == 'Used' ? 'selected' : '' }}>Used</option>
                    </select>
                    @error('condition')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Availability <span class="text-danger">*</span></label>
                    <select class="form-select @error('availability') is-invalid @enderror" name="availability" required>
                        <option value="Available" {{ old('availability', $vehicle->availability) == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Reserved" {{ old('availability', $vehicle->availability) == 'Reserved' ? 'selected' : '' }}>Reserved</option>
                        <option value="Sold Out" {{ old('availability', $vehicle->availability) == 'Sold Out' ? 'selected' : '' }}>Sold Out</option>
                    </select>
                    @error('availability')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Price ($) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                           name="price" value="{{ old('price', $vehicle->price) }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Existing Photos</h5>
        </div>
        <div class="card-body">
            @if($vehicle->photos->count() > 0)
                <div class="row g-3">
                    @foreach($vehicle->photos as $photo)
                        <div class="col-md-2">
                            <div class="position-relative">
                                <img src="{{ Storage::url($photo->photo_path) }}" class="img-fluid rounded" alt="Vehicle photo">
                                <form method="POST" action="{{ route('vehicles.delete-photo', $photo) }}" 
                                      onsubmit="return confirm('Delete this photo?');" class="position-absolute top-0 end-0 m-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">No photos uploaded yet.</p>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Add More Photos/Video</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Additional Photos</label>
                    <input type="file" class="form-control" name="photos[]" multiple accept="image/*">
                    <small class="text-muted">Max 10 photos total, 5MB each</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Video</label>
                    <input type="file" class="form-control" name="video" accept="video/*">
                    <small class="text-muted">Max 50MB</small>
                    @if($vehicle->video_path)
                        <div class="mt-2">
                            <span class="badge bg-info">Video already uploaded</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Vehicle
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
