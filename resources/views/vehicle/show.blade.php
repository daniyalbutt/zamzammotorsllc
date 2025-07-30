@extends('layouts.app')
@section('content')
    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vehicles.index') }}">Vehicles</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View Vehicle</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-12 col-xl-12 col-lg-12">
            <div class="card__wrapper">
                <div class="card__title-wrap mb-20 row">
                    <div class="col-md-12">
                        <h5 class="card__heading-title">Vehicle Details</h5>
                    </div>
                </div>

                <div class="row gx-0 g-20 gy-20 align-items-center justify-content-center">
                    <div class="box-body">
                        @if (session()->has('success'))
                            <div class="alert alert-success">
                                {{ session()->get('success') }}
                            </div>
                        @endif

                        <div class="row gy-3">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Title</label>
                                    <div class="form-control-plaintext">{{ $data->title }}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Condition</label>
                                    <div class="form-control-plaintext">{{ $data->condition }}</div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Content</label>
                                    <div class="form-control-plaintext">{{ $data->content }}</div>
                                </div>
                            </div>

                            <!-- Vehicle Details -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Make</label>
                                    <div class="form-control-plaintext">{{ $data->make->name ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Model</label>
                                    <div class="form-control-plaintext">{{ $data->model->name ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Body Type</label>
                                    <div class="form-control-plaintext">{{ $data->bodyType->name ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Year</label>
                                    <div class="form-control-plaintext">{{ $data->year }}</div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Offer Type</label>
                                    <div class="form-control-plaintext">{{ $data->offer_type ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Drive Type</label>
                                    <div class="form-control-plaintext">{{ $data->drive_type ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Transmission</label>
                                    <div class="form-control-plaintext">{{ $data->transmission ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <!-- Engine & Exterior -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Fuel Type</label>
                                    <div class="form-control-plaintext">{{ $data->fuel_type ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Cylinders</label>
                                    <div class="form-control-plaintext">{{ $data->cylinders ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Color</label>
                                    <div class="form-control-plaintext">{{ $data->color ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Doors</label>
                                    <div class="form-control-plaintext">{{ $data->doors ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <!-- Features -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Features</label>
                                    <div class="form-control-plaintext">
                                        @if($data->features && count($data->features) > 0)
                                            {{ implode(', ', $data->features) }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Safety Features</label>
                                    <div class="form-control-plaintext">
                                        @if($data->safety_features && count($data->safety_features) > 0)
                                            {{ implode(', ', $data->safety_features) }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-{{ $data->status ? 'success' : 'danger' }}">
                                            {{ $data->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Images -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Images</label>
                                    @if($data->image_paths && count($data->image_paths) > 0)
                                        <div class="row">
                                            @foreach($data->image_paths as $imagePath)
                                                <div class="col-md-3 mb-3">
                                                    <img src="{{ asset('storage/' . $imagePath) }}" 
                                                         class="img-fluid img-thumbnail" 
                                                         style="height: 200px; width: 100%; object-fit: cover;">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-info">No images available</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12">
                                <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Back</a>
                                @can('edit-vehicles')
                                    <a href="{{ route('vehicles.edit', $data->id) }}" class="btn btn-primary">Edit</a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .form-control-plaintext {
            padding: 0.375rem 0;
            margin-bottom: 0;
            line-height: 1.5;
            background-color: transparent;
            border: solid transparent;
            border-width: 1px 0;
        }
        
        .img-thumbnail {
            max-width: 100%;
            height: auto;
            padding: 0;
        }
    </style>
@endpush