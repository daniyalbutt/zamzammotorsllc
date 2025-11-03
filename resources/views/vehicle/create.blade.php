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
                    @if ($data)
                        @role('agent')
                            @php
                                $vechile_assigned = DB::table('assigned_vehicles')
                                    ->where('vehicle_id', $data->id)
                                    ->first();
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Title <strong>*</strong></label>
                                        <input class="form-control" type="text" name="title"
                                            value="{{ old('title', $data->title ?? '') }}" required />
                                    </div>
                                </div>

                                <div class="col-md-3">
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">RHD - LHD</label>
                                        <input type="text" class="form-control"
                                            value="{{ old('rhd_lhd', $data->rhd_lhd ?? '') }}" name="rhd_lhd">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Chasis/Engine No</label>
                                        <input type="number" class="form-control"
                                            value="{{ old('engine', $data->engine ?? '') }}" name="engine">
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea name="content" class="form-control" rows="4">{{ old('content', $data->content ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- Vehicle Details -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Make</label>
                                        <select name="make_id" class="select2-dynmaic" id="make_id">
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

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Model</label>
                                        <select name="model_id" class="select2-dynmaic" id="model_id">
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

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Body Type</label>
                                        <select name="body_type_id" class="select2-dynmaic">
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
                                        <label class="form-label">Stock ID</label>
                                        <input type="number" name="stock_id" class="form-control"
                                            value="{{ old('year', $data->stock_id ?? '') }}" />
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
                                        <label class="form-label">Mileage Field (km)</label>
                                        <input type="number" name="mileage" class="form-control"
                                            value="{{ old('mileage', $data->mileage ?? '') }}" min="0" />
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Features (comma separated)</label>
                                        <input name="features" class="tagify"
                                            value="{{ old('features', isset($data) && $data->features ? implode(',', $data->features) : '') }}" />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Safety Features (comma separated)</label>
                                        <input name="safety_features" class="tagify"
                                            value="{{ old('safety_features', isset($data) && $data->safety_features ? implode(',', $data->safety_features) : '') }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="form-label">Availabilty</label>
                                        <select name="availability" id="availability" class="form-control">
                                            <option value="available"
                                                {{ old('availability', $data->availability ?? '') == 'available' ? 'selected' : '' }}>
                                                Available</option>
                                            <option value="reserval"
                                                {{ old('availability', $data->availability ?? '') == 'reserval' ? 'selected' : '' }}>
                                                Reserval</option>
                                            <option value="not available"
                                                {{ old('availability', $data->availability ?? '') == 'not available' ? 'selected' : '' }}>
                                                Not Available</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Vehicle Video</label>
                                        <input type="file" name="video" id="video-upload" class="dropify"
                                            data-max-file-size="100M" data-allowed-file-extensions="mp4 mov avi mkv webm"
                                            data-max-height="300"
                                            @if (isset($data) && $data->video) data-default-file="{{ asset('storage/' . $data->video) }}" @endif />
                                        <small class="form-text text-muted">
                                            Accepted formats: MP4, MOV, AVI, MKV, WebM (Max size: 100MB)
                                        </small>

                                        @if (isset($data) && $data->video)
                                            <div class="mt-3 existing-video-preview" id="video-container">
                                                <div class="card">
                                                    <div
                                                        class="card-header d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0">Current Video</h6>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            data-path="{{ $data->video }}"
                                                            data-vehicle-id="{{ $data->id }}" id="remove-video">
                                                            <i class="fa-solid fa-trash"></i> Remove Video
                                                        </button>
                                                    </div>
                                                    <div class="card-body">
                                                        <video width="100%" height="300" controls class="rounded">
                                                            <source src="{{ asset('storage/' . $data->video) }}"
                                                                type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                        <div class="mt-2">
                                                            <a href="{{ asset('storage/' . $data->video) }}"
                                                                target="_blank" class="btn btn-sm btn-outline-primary">
                                                                <i class="fa-solid fa-external-link"></i> Open in New Tab
                                                            </a>
                                                            <a href="{{ asset('storage/' . $data->video) }}" download
                                                                class="btn btn-sm btn-outline-secondary">
                                                                <i class="fa-solid fa-download"></i> Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
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

        /* Dropify Custom Styles for Video */
        .dropify-wrapper {
            border: 2px dashed #0087F7;
            border-radius: 8px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .dropify-wrapper:hover {
            border-color: #0056b3;
            background: #e9ecef;
        }

        .dropify-wrapper .dropify-message {
            padding: 40px 20px;
        }

        .dropify-wrapper .dropify-message p {
            font-size: 16px;
            color: #495057;
        }

        .dropify-wrapper .dropify-preview {
            padding: 20px;
        }

        /* Video Preview Card Styles */
        .existing-video-preview .card {
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .existing-video-preview .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 12px 20px;
        }

        .existing-video-preview video {
            border: 1px solid #dee2e6;
            background: #000;
        }

        /* Dropify Error Styles */
        .dropify-wrapper.has-error {
            border-color: #dc3545;
        }

        .dropify-errors-container {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
        integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        Dropzone.autoDiscover = false;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify@4.35.3/dist/tagify.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Dropify for video upload with enhanced options
            let videoDropify = $('.dropify').dropify({
                messages: {
                    default: '<i class="fa-solid fa-video fa-3x mb-3"></i><br>Drag and drop a video here or click to browse',
                    replace: '<i class="fa-solid fa-video fa-3x mb-3"></i><br>Drag and drop or click to replace',
                    remove: '<i class="fa-solid fa-trash"></i> Remove',
                    error: 'Error: Please check the file size and format'
                },
                error: {
                    'fileSize': 'The file size is too large (max {{ $maxSize ?? '100MB' }}).',
                    'minWidth': 'The video width is too small.',
                    'maxWidth': 'The video width is too large.',
                    'minHeight': 'The video height is too small.',
                    'maxHeight': 'The video height is too large.',
                    'imageFormat': 'The video format is not allowed ({{ $formats ?? 'MP4, MOV, AVI, MKV, WebM' }}).'
                },
                tpl: {
                    wrap: '<div class="dropify-wrapper"></div>',
                    loader: '<div class="dropify-loader"></div>',
                    message: '<div class="dropify-message"><span class="file-icon"></span> <p>Drop Video</p></div>',
                    preview: '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p><p class="dropify-infos-message">Drop Video</p></div></div></div>',
                    filename: '<p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p>',
                    clearButton: '<button type="button" class="dropify-clear">remove </button>',
                    errorLine: '<p class="dropify-error">error</p>',
                    errorsContainer: '<div class="dropify-errors-container"><ul></ul></div>'
                }
            });

            // Handle Dropify events
            let drEvent = $('#video-upload').dropify();

            drEvent.on('dropify.beforeClear', function(event, element) {
                return confirm("Are you sure you want to remove this video?");
            });

            drEvent.on('dropify.afterClear', function(event, element) {
                console.log('Video removed');
            });

            drEvent.on('dropify.errors', function(event, element) {
                console.log('Dropify errors', element);
                alert('Please check the video file. Ensure it is a valid video format and under 100MB.');
            });

            // Remove existing video
            $(document).on('click', '.remove-existing-video', function(e) {
                e.preventDefault();
                const videoPath = $(this).data('path');

                if (confirm('Are you sure you want to remove this video? This action cannot be undone.')) {
                    // Add hidden input to mark video for deletion
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'remove_video',
                        value: '1'
                    }).appendTo('#main-form');

                    // Hide the existing video container
                    $('#existing-video-container').fadeOut(300, function() {
                        $(this).remove();
                    });

                    // Show success message
                    alert('Video marked for removal. Save the form to complete the action.');
                }
            });

            // Video file validation before upload
            $('#video-upload').on('change', function(e) {
                const file = e.target.files[0];

                if (file) {
                    const fileSize = file.size / 1024 / 1024; // Convert to MB
                    const allowedExtensions = ['mp4', 'mov', 'avi', 'mkv', 'webm'];
                    const fileExtension = file.name.split('.').pop().toLowerCase();

                    // Check file size
                    if (fileSize > 100) {
                        alert('Video file is too large. Maximum size is 100MB.');
                        $(this).val('');
                        return false;
                    }

                    // Check file extension
                    if (!allowedExtensions.includes(fileExtension)) {
                        alert('Invalid video format. Allowed formats: MP4, MOV, AVI, MKV, WebM');
                        $(this).val('');
                        return false;
                    }

                    console.log('Video file validated:', file.name, fileSize.toFixed(2) + ' MB');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Initialize Tagify
            new Tagify(document.querySelector('input[name="features"]'));
            new Tagify(document.querySelector('input[name="safety_features"]'));

            // Initialize Image Dropzone
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
                    console.log("Image Dropzone initialized");
                }
            });

            // Initialize Dropify for video upload
            $('.dropify').dropify({
                messages: {
                    default: 'Drop a video here or click to upload',
                    replace: 'Drop a video here or click to replace',
                    remove: 'Remove',
                    error: 'Error: The file is too large or not a valid video format'
                }
            });

            // Submit Form
            $('#main-form').submit(function(e) {
                e.preventDefault();
                let form = document.getElementById('main-form');
                let formData = new FormData(form);

                // Add Image Dropzone files to FormData
                myDropzone.getAcceptedFiles().forEach((file) => {
                    formData.append('images[]', file);
                });

                // Video is already included in the form data via the file input

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

            // Remove existing video
            $(document).on('click', '.remove-existing-video', function(e) {
                e.preventDefault();
                const videoPath = $(this).data('path');
                if (confirm('Remove this video?')) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'remove_video',
                        value: 1
                    }).appendTo('#main-form');
                    $(this).closest('.position-relative').remove();
                }
            });

            // Remove video via AJAX
            $('#remove-video').click(function() {
                const videoPath = $(this).data('path');
                const vehicleId = $(this).data('vehicle-id');

                if (confirm('Are you sure you want to remove this video?')) {
                    $.ajax({
                        url: "{{ route('vehicles.delete-video') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            video_path: videoPath,
                            vehicle_id: vehicleId
                        },
                        success: function(response) {
                            if (response.success) {
                                // Remove the video element from the DOM
                                $('#video-container').remove();
                                alert('Video removed successfully!');
                            } else {
                                alert('Failed to remove video: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            alert('Error removing video: ' + xhr.responseText);
                            console.log(xhr.responseText);
                        }
                    });
                }
            })

            $('.select2').select2();
            $('.select2-dynmaic').select2({
                tags: true
            });
        });
    </script>
@endpush
