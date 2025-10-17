@extends('layouts.app')
@section('content')

    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Employees</li>
                    <li class="breadcrumb-item active" aria-current="page">Add Employees</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="col-xxl-12">
        <div class="card__wrapper">

            <form class="form" method="post"
                action="{{ $data ? route('employees.update', $data->id) : route('employees.store') }}"
                enctype="multipart/form-data">
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

                    <div class="employee-details">

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

                                    <div class="col-12 mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control" name="meta[address]"
                                            value="{{ $data ? $data->getMeta('address') : old('meta[address]') }}">
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
                                        <label class="form-label">Designation <strong>*</strong></label>
                                        <input type="text" class="form-control" name="meta[designation]"
                                            value="{{ $data ? $data->getMeta('designation') : old('meta[designation]') }}"
                                            required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Department <strong>*</strong></label>
                                        <select class="form-control" name="meta[department_id]" required>
                                            <option value="">Select Department</option>
                                            @foreach ($departments as $department)
                                                <option
                                                    {{ $data ? ($data->getMeta('department_id') == $department->id ? 'selected' : '') : '' }}
                                                    value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date of Joining</label>
                                        <input type="date" class="form-control" name="meta[date_of_joining]"
                                            value="{{ $data ? $data->getMeta('date_of_joining') : old('meta[date_of_joining]') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Salary <strong>*</strong></label>
                                        <input type="number" class="form-control" name="meta[salary]"
                                            value="{{ $data ? $data->getMeta('salary') : old('meta[salary]') }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Shift Timings <strong>*</strong></label>
                                        <select class="form-control" name="meta[shift_id]" required>
                                            <option value="">Select Shift</option>
                                            @foreach ($shifts as $shift)
                                                <option
                                                    {{ $data ? ($data->getMeta('shift_id') == $shift->id ? 'selected' : '') : '' }}
                                                    value="{{ $shift->id }}">{{ $shift->name }} --
                                                    {{ $shift->start_time->format('h:i A') }} -
                                                    {{ $shift->end_time->format('h:i A') }}</option>
                                            @endforeach
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

                        <!-- Emergency Contact Section -->
                        <div class="col-xxl-12">
                            <div class="card__wrapper custom_wrapper">
                                <div class="card__title-wrap d-flex align-items-center justify-content-between">
                                    <h5 class="card__heading-title">Emergency Contact</h5>
                                </div>

                                <h6 class="section-title">Primary Contact</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="meta[primary_contact_name]"
                                            value="{{ $data ? $data->getMeta('primary_contact_name') : old('meta[primary_contact_name]') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Relationship</label>
                                        <input type="text" class="form-control"
                                            name="meta[primary_contact_relationship]"
                                            value="{{ $data ? $data->getMeta('primary_contact_relationship') : old('meta[primary_contact_relationship]') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" name="meta[primary_contact_phone]"
                                            value="{{ $data ? $data->getMeta('primary_contact_phone') : old('meta[primary_contact_phone]') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="meta[primary_contact_email]"
                                            value="{{ $data ? $data->getMeta('primary_contact_email') : old('meta[primary_contact_email]') }}">
                                    </div>

                                    <div class="col-12 mb-4">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control" name="meta[primary_contact_address]"
                                            value="{{ $data ? $data->getMeta('primary_contact_address') : old('meta[primary_contact_address]') }}">
                                    </div>
                                </div>

                                <h6 class="section-title">Secondary Contact</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="meta[secondary_contact_name]"
                                            value="{{ $data ? $data->getMeta('secondary_contact_name') : old('meta[secondary_contact_name]') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Relationship</label>
                                        <input type="text" class="form-control"
                                            name="meta[secondary_contact_relationship]"
                                            value="{{ $data ? $data->getMeta('secondary_contact_relationship') : old('meta[secondary_contact_relationship]') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" name="meta[secondary_contact_phone]"
                                            value="{{ $data ? $data->getMeta('secondary_contact_phone') : old('meta[secondary_contact_phone]') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="meta[secondary_contact_email]"
                                            value="{{ $data ? $data->getMeta('secondary_contact_email') : old('meta[secondary_contact_email]') }}">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control" name="meta[secondary_contact_address]"
                                            value="{{ $data ? $data->getMeta('secondary_contact_address') : old('meta[secondary_contact_address]') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Education Qualification Section -->
                        <div class="col-xxl-12">
                            <div class="card__wrapper custom_wrapper">
                                <div class="card__title-wrap d-flex align-items-center justify-content-between">
                                    <h5 class="card__heading-title">Education Qualification</h5>
                                    <button type="button" class="btn btn-sm btn-outline-primary add-repeater-item"
                                        data-repeater="education">
                                        <i class="fa-solid fa-plus"></i> Add
                                    </button>
                                </div>

                                <div id="education-repeater" class="repeater" data-name="education">
                                    <!-- Education items will be added here dynamically -->
                                    @if ($data && $data->getMeta('education'))
                                        @foreach (json_decode($data->getMeta('education'), true) as $index => $education)
                                            <div class="repeater-item">
                                                <span class="remove-item" onclick="removeRepeaterItem(this)"><i
                                                        class="fa-solid fa-times"></i></span>

                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Institute Name</label>
                                                        <input type="text" class="form-control"
                                                            name="meta[education][{{ $index }}][institute]"
                                                            value="{{ $education['institute'] ?? '' }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Degree</label>
                                                        <input type="text" class="form-control"
                                                            name="meta[education][{{ $index }}][degree]"
                                                            value="{{ $education['degree'] ?? '' }}">
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Start Year</label>
                                                        <input type="number" class="form-control"
                                                            name="meta[education][{{ $index }}][start_year]"
                                                            value="{{ $education['start_year'] ?? '' }}">
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">End Year</label>
                                                        <input type="number" class="form-control"
                                                            name="meta[education][{{ $index }}][end_year]"
                                                            value="{{ $education['end_year'] ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Experience Details Section -->
                        <div class="col-xxl-12">
                            <div class="card__wrapper custom_wrapper">
                                <div class="card__title-wrap d-flex align-items-center justify-content-between">
                                    <h5 class="card__heading-title">Experience Details</h5>
                                    <button type="button" class="btn btn-sm btn-outline-primary add-repeater-item"
                                        data-repeater="experience">
                                        <i class="fa-solid fa-plus"></i> Add
                                    </button>
                                </div>

                                <div id="experience-repeater" class="repeater" data-name="experience">
                                    <!-- Experience items will be added here dynamically -->
                                    @if ($data && $data->getMeta('experience'))
                                        @foreach (json_decode($data->getMeta('experience'), true) as $index => $experience)
                                            <div class="repeater-item">
                                                <span class="remove-item" onclick="removeRepeaterItem(this)"><i
                                                        class="fa-solid fa-times"></i></span>

                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Company Name</label>
                                                        <input type="text" class="form-control"
                                                            name="meta[experience][{{ $index }}][company]"
                                                            value="{{ $experience['company'] ?? '' }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Position</label>
                                                        <input type="text" class="form-control"
                                                            name="meta[experience][{{ $index }}][position]"
                                                            value="{{ $experience['position'] ?? '' }}">
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Start Year</label>
                                                        <input type="number" class="form-control"
                                                            name="meta[experience][{{ $index }}][start_year]"
                                                            value="{{ $experience['start_year'] ?? '' }}">
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">End Year</label>
                                                        <input type="number" class="form-control"
                                                            name="meta[experience][{{ $index }}][end_year]"
                                                            value="{{ $experience['end_year'] ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Bank Account Section -->
                        <div class="col-xxl-12">
                            <div class="card__wrapper custom_wrapper">
                                <div class="card__title-wrap d-flex align-items-center justify-content-between">
                                    <h5 class="card__heading-title">Bank Account</h5>
                                </div>

                                <div class="row">
                                    <div class="col-4 mb-3">
                                        <label class="form-label">Account Holder Name</label>
                                        <input type="text" class="form-control" name="meta[account_holder_name]"
                                            value="{{ $data ? $data->getMeta('account_holder_name') : old('meta[account_holder_name]') }}">
                                    </div>

                                    <div class="col-4 mb-3">
                                        <label class="form-label">Account Number</label>
                                        <input type="text" class="form-control" name="meta[account_number]"
                                            value="{{ $data ? $data->getMeta('account_number') : old('meta[account_number]') }}">
                                    </div>

                                    <div class="col-4 mb-3">
                                        <label class="form-label">Bank Name</label>
                                        <input type="text" class="form-control" name="meta[bank_name]"
                                            value="{{ $data ? $data->getMeta('bank_name') : old('meta[bank_name]') }}">
                                    </div>

                                    <div class="col-6 mb-3">
                                        <label class="form-label">Branch Name</label>
                                        <input type="text" class="form-control" name="meta[branch_name]"
                                            value="{{ $data ? $data->getMeta('branch_name') : old('meta[branch_name]') }}">
                                    </div>

                                    <div class="col-6 mb-3">
                                        <label class="form-label">SWIFT Code</label>
                                        <input type="text" class="form-control" name="meta[swift_code]"
                                            value="{{ $data ? $data->getMeta('swift_code') : old('meta[swift_code]') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Passport Information Section -->
                        <div class="col-xxl-12">
                            <div class="card__wrapper custom_wrapper">
                                <div class="card__title-wrap d-flex align-items-center justify-content-between">
                                    <h5 class="card__heading-title">Passport Information</h5>
                                </div>

                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Passport Number</label>
                                        <input type="text" class="form-control" name="meta[passport_number]"
                                            value="{{ $data ? $data->getMeta('passport_number') : old('meta[passport_number]') }}">
                                    </div>

                                    <div class="col-6 mb-3">
                                        <label class="form-label">Nationality</label>
                                        <input type="text" class="form-control" name="meta[nationality]"
                                            value="{{ $data ? $data->getMeta('nationality') : old('meta[nationality]') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Issue Date</label>
                                        <input type="date" class="form-control" name="meta[passport_issue_date]"
                                            value="{{ $data ? $data->getMeta('passport_issue_date') : old('meta[passport_issue_date]') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Expiry Date</label>
                                        <input type="date" class="form-control" name="meta[passport_expiry_date]"
                                            value="{{ $data ? $data->getMeta('passport_expiry_date') : old('meta[passport_expiry_date]') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">Save Employee</button>
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
        // Universal Repeater Functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all repeaters
            initializeRepeaters();

            // Add event listeners to all add buttons
            document.querySelectorAll('.add-repeater-item').forEach(button => {
                button.addEventListener('click', function() {
                    const repeaterName = this.getAttribute('data-repeater');
                    addRepeaterItem(repeaterName);
                });
            });
        });

        function initializeRepeaters() {
            document.querySelectorAll('.repeater').forEach(repeater => {
                const repeaterName = repeater.getAttribute('data-name');
                const items = repeater.querySelectorAll('.repeater-item');

                // If no items exist, add one
                if (items.length === 0) {
                    addRepeaterItem(repeaterName);
                }
            });
        }

        function addRepeaterItem(repeaterName) {
            const container = document.getElementById(`${repeaterName}-repeater`);
            const itemCount = container.querySelectorAll('.repeater-item').length;

            let itemHTML = '';

            // Define templates for different repeater types
            if (repeaterName === 'education') {
                itemHTML = `
            <div class="repeater-item">
                <span class="remove-item" onclick="removeRepeaterItem(this)"><i class="fa-solid fa-times"></i></span>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label">Institute Name</label>
                        <input type="text" class="form-control" name="meta[${repeaterName}][${itemCount}][institute]" value="">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Degree</label>
                        <input type="text" class="form-control" name="meta[${repeaterName}][${itemCount}][degree]" value="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Start Year</label>
                        <input type="number" class="form-control" name="meta[${repeaterName}][${itemCount}][start_year]" value="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">End Year</label>
                        <input type="number" class="form-control" name="meta[${repeaterName}][${itemCount}][end_year]" value="">
                    </div>
                </div>
            </div>
        `;
            } else if (repeaterName === 'experience') {
                itemHTML = `
            <div class="repeater-item">
                <span class="remove-item" onclick="removeRepeaterItem(this)"><i class="fa-solid fa-times"></i></span>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" class="form-control" name="meta[${repeaterName}][${itemCount}][company]" value="">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Position</label>
                        <input type="text" class="form-control" name="meta[${repeaterName}][${itemCount}][position]" value="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Start Year</label>
                        <input type="number" class="form-control" name="meta[${repeaterName}][${itemCount}][start_year]" value="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">End Year</label>
                        <input type="number" class="form-control" name="meta[${repeaterName}][${itemCount}][end_year]" value="">
                    </div>
                </div>
            </div>
        `;
            }

            if (itemHTML) {
                container.insertAdjacentHTML('beforeend', itemHTML);
            }
        }

        function removeRepeaterItem(element) {
            element.closest('.repeater-item').remove();
        }
        
        
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

        .employee-details .custom_wrapper .card__title-wrap {
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

        .repeater-item {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            position: relative;
            border: 1px solid #e9ecef;
        }

        .remove-item {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #dc3545;
            cursor: pointer;
            font-size: 18px;
        }

        .add-repeater-item {
            margin-bottom: 0;
        }
    </style>
@endpush
