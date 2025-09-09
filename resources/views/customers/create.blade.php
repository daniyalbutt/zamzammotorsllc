@extends('layouts.app')
@section('content')

    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Customers</li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $data ? 'Edit' : 'Add' }} Customer</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="col-xxl-12">
        <div class="card__wrapper">

            <form class="form" method="post"
                action="{{ $data ? route('customers.update', $data->id) : route('customers.store') }}" enctype="multipart/form-data">
                @if ($data)
                    @method('PUT')
                @endif

                <div class="row">
                    @csrf
                    @if ($errors->any())
                        {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
                    @endif
                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    <div class="customer-details">

                        <!-- Personal Information Section -->
                        <div class="col-xxl-12">
                            <div class="card__wrapper custom_wrapper">
                                <div class="card__title-wrap d-flex align-items-center justify-content-between">
                                    <h5 class="card__heading-title">Personal Information</h5>
                                    <hr>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Add Image profile circle avatar input file  -->
                                        <div class="col-md-12 mb-4">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="avatar-upload me-4">
                                                    <div class="avatar-edit">
                                                        <input type="file" id="imageUpload" name="image"
                                                            accept=".png, .jpg, .jpeg">
                                                        <label for="imageUpload"></label>
                                                    </div>
                                                    <div class="avatar-preview">
                                                        <div id="imagePreview"
                                                            style="background-image: url('{{ $data && $data->image ? asset($data->image) : asset('img/user.png') }}');">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Name <strong>*</strong></label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ $data ? $data->name : old('name') }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email <strong>*</strong></label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ $data ? $data->email : old('email') }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" name="meta[phone]"
                                            value="{{ $data ? $data->getMeta('phone') : old('meta[phone]') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Birthday</label>
                                        <input type="date" class="form-control" name="meta[birthday]"
                                            value="{{ $data ? $data->getMeta('birthday') : old('meta[birthday]') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control" name="meta[address]"
                                            value="{{ $data ? $data->getMeta('address') : old('meta[address]') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Assign to Agent</label>
                                        <select name="assigned" id="assigned" class="form-control">
                                            <option value="Not Assign">Not Assigned to Any Agent</option>
                                            @foreach ($agents as $item)
                                                @php
                                                    $exists = false;
                                                    if ($data) {
                                                        $exists = \DB::table('assigned_agents')
                                                            ->where('agent_id', $item->id)
                                                            ->where('customer_id', $data->id)
                                                            ->exists();
                                                        }
                                                @endphp
                                                <option {{$exists ? 'selected' : ''}}
                                                    value="{{ $item->id }}">{{ $item->name }} -- {{ $item->email }}</option>
                                            @endforeach
        
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Gender</label>
                                        <select class="form-control" name="meta[gender]">
                                            <option value="">Select Gender</option>
                                            <option value="male"
                                                {{ $data && $data->getMeta('gender') == 'male' ? 'selected' : '' }}>Male
                                            </option>
                                            <option value="female"
                                                {{ $data && $data->getMeta('gender') == 'female' ? 'selected' : '' }}>
                                                Female
                                            </option>
                                            <option value="other"
                                                {{ $data && $data->getMeta('gender') == 'other' ? 'selected' : '' }}>Other
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Password {!! !$data ? '<strong>*</strong>' : '' !!}</label>
                                        <input type="password" class="form-control" name="password"
                                            {{ !$data ? 'required' : '' }}
                                            placeholder="{{ $data ? 'Leave blank to keep current password' : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Links Section -->
                        <div class="col-xxl-12">
                            <div class="card__wrapper custom_wrapper">
                                <div class="card__title-wrap d-flex align-items-center justify-content-between">
                                    <h5 class="card__heading-title">Social Media Links</h5>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Facebook</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-sharp fa-light fa-user"></i></span>
                                            <input type="url" class="form-control" name="meta[facebook]"
                                                value="{{ $data ? $data->getMeta('facebook') : old('meta[facebook]') }}"
                                                placeholder="https://facebook.com/username">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">LinkedIn</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fab fa-linkedin-in"></i></span>
                                            <input type="url" class="form-control" name="meta[linkedin]"
                                                value="{{ $data ? $data->getMeta('linkedin') : old('meta[linkedin]') }}"
                                                placeholder="https://linkedin.com/in/username">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Twitter</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                            <input type="url" class="form-control" name="meta[twitter]"
                                                value="{{ $data ? $data->getMeta('twitter') : old('meta[twitter]') }}"
                                                placeholder="https://twitter.com/username">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">YouTube</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                            <input type="url" class="form-control" name="meta[youtube]"
                                                value="{{ $data ? $data->getMeta('youtube') : old('meta[youtube]') }}"
                                                placeholder="https://youtube.com/username">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Instagram</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                            <input type="url" class="form-control" name="meta[instagram]"
                                                value="{{ $data ? $data->getMeta('instagram') : old('meta[instagram]') }}"
                                                placeholder="https://instagram.com/username">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Website</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                            <input type="url" class="form-control" name="meta[website]"
                                                value="{{ $data ? $data->getMeta('website') : old('meta[website]') }}"
                                                placeholder="https://example.com">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">Save Customer</button>
                            <button type="button" class="btn btn-light ms-2">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('js')
    <script>
        // Image upload preview
        function readURL(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').style.backgroundImage = 'url(' + e.target.result + ')';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('imageUpload').addEventListener('change', function() {
            readURL(this);
        });
    </script>
@endpush

@push('css')
    <style>
        .avatar-upload {
            position: relative;
            max-width: 130px;
        }

        .avatar-upload .avatar-edit {
            position: absolute;
            right: 12px;
            z-index: 1;
            bottom: 10px;
        }

        .avatar-upload .avatar-edit input {
            display: none;
        }

        .avatar-upload .avatar-edit input+label {
            display: inline-block;
            width: 34px;
            height: 34px;
            margin-bottom: 0;
            border-radius: 100%;
            background: #FFFFFF;
            border: 1px solid transparent;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            font-weight: normal;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-upload .avatar-edit input+label:after {
            content: "\f030";
            font-family: 'FontAwesome';
            color: #757575;
        }

        .avatar-upload .avatar-edit input+label:hover {
            background: #f1f1f1;
            border-color: #d6d6d6;
        }

        .avatar-upload .avatar-preview {
            width: 120px;
            height: 120px;
            position: relative;
            border-radius: 100%;
            border: 6px solid #F8F8F8;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
        }

        .avatar-upload .avatar-preview>div {
            width: 100%;
            height: 100%;
            border-radius: 100%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        .customer-details .custom_wrapper .card__title-wrap {
            border-bottom: 1px solid;
            border-color: #c9ccdf;
            padding: 14px 0;
            margin-bottom: 14px;
        }

        .card__wrapper.custom_wrapper .section-title {
            margin-bottom: 1rem;
            font-size: 16px;
        }

        .custom_wrapper {
            background: transparent !important;
            box-shadow: none;
            padding: 0;
        }

        .input-group-text {
            background-color: #f8f9fa;
        }
    </style>
@endpush