@extends('layouts.app')
@section('content')
    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vehicles.index') }}">Vehicles</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ isset($data) ? 'Edit' : 'Add' }} Vehicle</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-12 col-xl-12 col-lg-12">
            <div class="card__wrapper">
                <div class="card__title-wrap mb-20 row">
                    <div class="col-md-9">
                        <h5 class="card__heading-title">Vehicle Form</h5>
                    </div>
                    @if($data)
                    @role('agent')
                        @php
                         $vechile_assigned = DB::table('assigned_vehicles')->where('vehicle_id', $data->id)->first();
                        @endphp

                        <div class="col-md-3">
                            @if ($vechile_assigned && $vechile_assigned->assigned_by == Auth::id())
                                <select name="assigned" id="assigned" class="form-control user-select select2">
                                    <option value="Not Assign">Not Assigned Customer</option>
                                    @foreach ($users->get() as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $data->assigned_users->contains($item->id) ? 'selected' : '' }}>
                                            {{ $item->name }} -- {{ $item->email }}
                                        </option>
                                    @endforeach
                                </select>
                            @elseif ($vechile_assigned && $vechile_assigned->assigned_by != Auth::id())
                                @php
                                    $ass = DB::table('users')->where('id', $vechile_assigned->user_id)->first();
                                    $cus = DB::table('users')->where('id', $vechile_assigned->assigned_by)->first();
                                @endphp
                                <span class="text-success text-end">
                                    Assigned to: {{ $ass->name }}
                                </span><span class="text-success text-end">
                                    By Agent: {{ $cus->name }}
                                </span>
                            @elseif (!$vechile_assigned)
                                <select name="assigned" id="assigned" class="form-control user-select select2">
                                    <option value="Not Assign">Not Assigned Customer</option>
                                        @foreach ($users->get() as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $data->assigned_users->contains($item->id) ? 'selected' : '' }}>
                                                {{ $item->name }} -- {{ $item->email }}
                                            </option>
                                        @endforeach
                                </select>
                            @endif
                        </div>
                    @endrole
                    @endif


                </div>
                <form class="form" method="post" id="main-form"
                    action="{{ isset($data) ? route('vehicles.update', $data->id) : route('vehicles.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @if (isset($data))
                        @method('PUT')
                    @endif

                    <div class="row gx-0 g-20 gy-20 align-items-center justify-content-center">
                        <div class="box-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif

                            <div class="row gy-3">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Title <strong>*</strong></label>
                                        <input class="form-control" type="text" name="title"
                                            value="{{ old('title', $data->title ?? '') }}" required />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Condition</label>
                                        <select name="condition" class="form-control">
                                            <option value="Used"
                                                {{ old('condition', $data->condition ?? '') == 'Used' ? 'selected' : '' }}>
                                                Used</option>
                                            <option value="New"
                                                {{ old('condition', $data->condition ?? '') == 'New' ? 'selected' : '' }}>
                                                New</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Content</label>
                                        <textarea name="content" class="form-control" rows="4">{{ old('content', $data->content ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- Vehicle Details -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Make</label>
                                        <select name="make_id" class="form-control" id="make_id">
                                            <option value="">Select Make</option>
                                            @foreach ($make as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('make_id', $data->make_id ?? '') == $item->id ? 'selected' : '' }}>

                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Model</label>
                                        <select name="model_id" class="form-control" id="model_id">
                                            <option value="">Select Model</option>
                                            @foreach ($model as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('model_id', $data->model_id ?? '') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>

                                                {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Body Type</label>
                                        <select name="body_type_id" class="form-control">
                                            <option value="">Select Body Type</option>
                                            @foreach ($body_type as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('body_type_id', $data->body_type_id ?? '') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Year</label>
                                        <input type="number" name="year" class="form-control"
                                            value="{{ old('year', $data->year ?? '') }}" min="1900"
                                            max="{{ date('Y') + 1 }}" />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Offer Type</label>
                                        <input type="text" name="offer_type" class="form-control"
                                            value="{{ old('offer_type', $data->offer_type ?? '') }}" />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Drive Type</label>
                                        <select name="drive_type" class="form-control">
                                            <option value="">Select Drive Type</option>
                                            <option value="AWD/4WD"
                                                {{ old('drive_type', $data->drive_type ?? '') == 'AWD/4WD' ? 'selected' : '' }}>
                                                AWD/4WD</option>
                                            <option value="Front Wheel Drive"
                                                {{ old('drive_type', $data->drive_type ?? '') == 'Front Wheel Drive' ? 'selected' : '' }}>
                                                Front Wheel Drive</option>
                                            <option value="Rear Wheel Drive"
                                                {{ old('drive_type', $data->drive_type ?? '') == 'Rear Wheel Drive' ? 'selected' : '' }}>
                                                Rear Wheel Drive</option>
                                        </select>

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Transmission</label>
                                        <select name="transmission" class="form-control">
                                            <option value="">Select Transmission</option>
                                            <option value="Automatic"
                                                {{ old('transmission', $data->transmission ?? '') == 'Automatic' ? 'selected' : '' }}>
                                                Automatic</option>
                                            <option value="Manual"
                                                {{ old('transmission', $data->transmission ?? '') == 'Manual' ? 'selected' : '' }}>
                                                Manual</option>
                                            <option value="CVT"
                                                {{ old('transmission', $data->transmission ?? '') == 'CVT' ? 'selected' : '' }}>
                                                CVT</option>
                                            <option value="Semi-Automatic"
                                                {{ old('transmission', $data->transmission ?? '') == 'Semi-Automatic' ? 'selected' : '' }}>
                                                Semi-Automatic</option>
                                        </select>

                                    </div>
                                </div>

                                <!-- Engine & Exterior -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Fuel Type</label>
                                        <select name="fuel_type" class="form-control">
                                            <option value="">Select Fuel Type</option>
                                            <option value="Diesel"
                                                {{ old('fuel_type', $data->fuel_type ?? '') == 'Diesel' ? 'selected' : '' }}>
                                                Diesel</option>
                                            <option value="Gasoline"
                                                {{ old('fuel_type', $data->fuel_type ?? '') == 'Gasoline' ? 'selected' : '' }}>
                                                Gasoline</option>
                                            <option value="Hybrid"
                                                {{ old('fuel_type', $data->fuel_type ?? '') == 'Hybrid' ? 'selected' : '' }}>
                                                Hybrid</option>
                                            <option value="Electric"
                                                {{ old('fuel_type', $data->fuel_type ?? '') == 'Electric' ? 'selected' : '' }}>
                                                Electric</option>
                                        </select>

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Cylinders</label>
                                        <input type="number" name="cylinders" class="form-control"
                                            value="{{ old('cylinders', $data->cylinders ?? '') }}" min="0" />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Color</label>
                                        <input type="text" name="color" class="form-control"
                                            value="{{ old('color', $data->color ?? '') }}" />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Doors</label>
                                        <input type="text" name="doors" class="form-control"
                                            value="{{ old('doors', $data->doors ?? '') }}" />
                                    </div>
                                </div>

                                <!-- Features -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Features (comma separated)</label>
                                        <input name="features" class="tagify"
                                            value="{{ old('features', isset($data) && $data->features ? implode(',', $data->features) : '') }}" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Safety Features (comma separated)</label>
                                        <input name="safety_features" class="tagify"
                                            value="{{ old('safety_features', isset($data) && $data->safety_features ? implode(',', $data->safety_features) : '') }}" />
                                    </div>
                                </div>

                                <!-- Images with Dropzone -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Images</label>
                                        <div id="image-dropzone" class="dropzone">
                                            <div class="dz-message">
                                                <div class="dz-icon">📁</div>
                                                <h3>Drop files here or click to upload</h3>
                                                <span>You can upload up to 10 images</span>
                                            </div>
                                        </div>

                                        @if (isset($data) && $data->image_paths && count($data->image_paths) > 0)
                                            <div class="mt-3">
                                                <h6>Existing Images:</h6>
                                                <div id="existing-images">
                                                    @foreach ($data->image_paths as $index => $imagePath)
                                                        <div class="position-relative"
                                                            id="existing-image-{{ $index }}">
                                                            <img src="{{ asset('storage/' . $imagePath) }}"
                                                                class="img-thumbnail" style="height: 100px;">
                                                            <button type="button" class=" remove-existing-image"
                                                                data-path="{{ $imagePath }}"
                                                                data-index="{{ $index }}"><i
                                                                    class="fa-solid fa-xmark"></i></button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="status"
                                                id="status" value="1"
                                                {{ old('status', isset($data) ? $data->status : 1) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary"
                                        id="submit-btn">{{ isset($data) ? 'Update' : 'Save' }} Vehicle</button>
                                    <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify@4.35.3/dist/tagify.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/6.0.0-beta.1/dropzone.min.css" rel="stylesheet">
    <style>
        .dropzone {
            border: 2px dashed #0087F7;
            border-radius: 5px;
            background: white;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            min-height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dropzone .dz-message {
            margin: 0;
        }

        .dropzone .dz-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .dropzone .dz-preview {
            display: inline-block;
            margin: 10px;
            vertical-align: top;
        }

        .dropzone .dz-preview .dz-image {
            border-radius: 5px;
            overflow: hidden;
        }

        .dropzone .dz-preview .dz-image img {
            width: 120px;
            height: 120px;
            object-fit: cover;
        }

        .dropzone .dz-preview .dz-remove {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            margin-top: 5px;
            font-size: 12px;
        }

        .dropzone .dz-preview .dz-remove:hover {
            background: #c82333;
        }

        .img-thumbnail {
            max-width: 100%;
            height: auto;
            padding: 0;
        }
    </style>
@endpush
@push('js')
    <script>
        Dropzone.autoDiscover = false;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify@4.35.3/dist/tagify.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Tagify
            new Tagify(document.querySelector('input[name="features"]'));
            new Tagify(document.querySelector('input[name="safety_features"]'));

            // Initialize Dropzone manually
            let myDropzone = new Dropzone("#image-dropzone", {
                url: "{{ route('vehicles.upload-image') }}",
                autoProcessQueue: false,
                paramName: "images",
                uploadMultiple: true,
                parallelUploads: 10,
                maxFiles: 10,
                addRemoveLinks: true,
                acceptedFiles: "image/*",
                dictDefaultMessage: "Drop files or click to upload",
                dictRemoveFile: "Remove",
                init: function() {
                    console.log("Dropzone initialized");
                }
            });

            // Submit Form
            $('#main-form').submit(function(e) {
                e.preventDefault();
                let form = document.getElementById('main-form');
                let formData = new FormData(form);

                // Add Dropzone files to FormData
                myDropzone.getAcceptedFiles().forEach((file) => {
                    formData.append('images[]', file);
                });

                // Add assigned user if exists
                let assignedValue = $('#assigned').find(':selected').val();
                if (assignedValue) {
                    formData.append('assigned', assignedValue);
                }

                // For Laravel PUT
                if (form.action.includes('update')) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: form.action,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(res) {
                        alert('Vehicle saved successfully!');
                        window.location.href = "{{ route('vehicles.index') }}";
                    },
                    error: function(xhr) {
                        alert('Something went wrong');
                        console.log(xhr.responseText);
                    }
                });
            });

            // Remove existing image
            $(document).on('click', '.remove-existing-image', function(e) {
                e.preventDefault();
                const imagePath = $(this).data('path');
                if (confirm('Remove this image?')) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'removed_images[]',
                        value: imagePath
                    }).appendTo('#main-form');
                    $(this).closest('.col-md-2').remove();
                }
            });

            $('.select2').select2();
        });
    </script>
@endpush
