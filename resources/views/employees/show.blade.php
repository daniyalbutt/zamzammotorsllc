@extends('layouts.app')

@section('content')
    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $data->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Personal Information -->
        <div class="col-xxl-7">
            <div class="card__wrapper height-equal">
                <div class="employee__profile-single-box p-relative">
                    <div class="card__title-wrap d-flex align-items-center justify-content-between mb-15">
                        <h5 class="card__heading-title">Personal Information</h5>
                        <a href="{{ route('employees.edit', $data->id) }}" class="edit-icon">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                    </div>
                    <div class="profile-view d-flex flex-wrap justify-content-between align-items-start">
                        <div class="d-flex flex-wrap align-items-start gap-20">
                            <div class="profile-img-wrap">
                                <div class="profile-img">
                                    <a href="#"><img src="{{ Auth::user()->profileImage() }}" alt="User Image"></a>
                                </div>
                            </div>
                            <div class="profile-info">
                                <h3 class="user-name mb-15">{{ $data->name }}</h3>
                                <h6 class="text-muted mb-5">{{ $data->getDepartment() }}</h6>
                                <span class="d-block text-muted mb-5">{{ $data->getMeta('designation') ?? 'N/A' }}</span>
                                <h6 class="small employee-id text-black mb-5">Employee ID : {{ $data->getMeta('employee_id') ?? 'EMP-' . str_pad($data->id, 4, '0', STR_PAD_LEFT) }}</h6>
                                <span class="d-block text-muted mb-20">Date of Join : {{ $data->getMeta('date_of_joining') ? \Carbon\Carbon::parse($data->getMeta('date_of_joining'))->format('d M Y') : 'N/A' }}</span>
                                
                            </div>
                        </div>
                        <div class="personal-info-wrapper pr-20">
                            <ul class="personal-info">
                                <li>
                                    <div class="title">Phone:</div>
                                    <div class="text text-link-hover">
                                        @if($data->getMeta('phone'))
                                            <a href="tel:{{ $data->getMeta('phone') }}">{{ $data->getMeta('phone') }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </li>
                                <li>
                                    <div class="title">Email:</div>
                                    <div class="text text-link-hover">
                                        <a href="mailto:{{ $data->email }}">{{ $data->email }}</a>
                                    </div>
                                </li>
                                <li>
                                    <div class="title">Birthday:</div>
                                    <div class="text">
                                        {{ $data->getMeta('birthday') ? \Carbon\Carbon::parse($data->getMeta('birthday'))->format('d F Y') : 'N/A' }}
                                    </div>
                                </li>
                                <li>
                                    <div class="title">Address:</div>
                                    <div class="text">{{ $data->getMeta('address') ?? 'N/A' }}</div>
                                </li>
                                <li>
                                    <div class="title">Gender:</div>
                                    <div class="text">{{ $data->getMeta('gender') ? ucfirst($data->getMeta('gender')) : 'N/A' }}</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="col-xxl-5">
            <div class="card__wrapper height-equal">
                <div class="employee__profile-single-box p-relative">
                    <div class="card__title-wrap d-flex align-items-center justify-content-between mb-15">
                        <h5 class="card__heading-title">Emergency Contact</h5>
                        <a href="{{ route('employees.edit', $data->id) }}" class="edit-icon">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="emergency-contact">
                                <h6 class="card__sub-title mb-10">Primary Contact</h6>
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Name:</div>
                                        <div class="text">{{ $data->getMeta('primary_contact_name') ?? 'N/A' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Relationship:</div>
                                        <div class="text">{{ $data->getMeta('primary_contact_relationship') ?? 'N/A' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Phone:</div>
                                        <div class="text text-link-hover">
                                            @if($data->getMeta('primary_contact_phone'))
                                                <a href="tel:{{ $data->getMeta('primary_contact_phone') }}">{{ $data->getMeta('primary_contact_phone') }}</a>
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </li>
                                    <li>
                                        <div class="title">Email:</div>
                                        <div class="text text-link-hover">
                                            @if($data->getMeta('primary_contact_email'))
                                                <a href="mailto:{{ $data->getMeta('primary_contact_email') }}">{{ $data->getMeta('primary_contact_email') }}</a>
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </li>
                                    <li>
                                        <div class="title">Address:</div>
                                        <div class="text">{{ $data->getMeta('primary_contact_address') ?? 'N/A' }}</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="emergency-contact">
                                <h6 class="card__sub-title mb-10">Secondary Contact</h6>
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Name:</div>
                                        <div class="text">{{ $data->getMeta('secondary_contact_name') ?? 'N/A' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Relationship:</div>
                                        <div class="text">{{ $data->getMeta('secondary_contact_relationship') ?? 'N/A' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Phone:</div>
                                        <div class="text text-link-hover">
                                            @if($data->getMeta('secondary_contact_phone'))
                                                <a href="tel:{{ $data->getMeta('secondary_contact_phone') }}">{{ $data->getMeta('secondary_contact_phone') }}</a>
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </li>
                                    <li>
                                        <div class="title">Email:</div>
                                        <div class="text text-link-hover">
                                            @if($data->getMeta('secondary_contact_email'))
                                                <a href="mailto:{{ $data->getMeta('secondary_contact_email') }}">{{ $data->getMeta('secondary_contact_email') }}</a>
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </li>
                                    <li>
                                        <div class="title">Address:</div>
                                        <div class="text">{{ $data->getMeta('secondary_contact_address') ?? 'N/A' }}</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Education Qualification -->
        <div class="col-xxl-6">
            <div class="card__wrapper">
                <div class="employee__profile-single-box p-relative">
                    <div class="card__title-wrap d-flex align-items-center justify-content-between mb-15">
                        <h5 class="card__heading-title">Education Qualification</h5>
                        <a href="{{ route('employees.edit', $data->id) }}" class="edit-icon">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                    </div>
                    <div class="education__box">
                        <ul class="education__list">
                            @php
                                $education = $data->getMeta('education') ? json_decode($data->getMeta('education'), true) : [];
                            @endphp
                            @if(!empty($education) && is_array($education))
                                @foreach($education as $edu)
                                    <li>
                                        <div class="education__user">
                                            <div class="before__circle"></div>
                                        </div>
                                        <div class="education__content">
                                            <div class="timeline-content">
                                                <a href="#" class="name">{{ $edu['institute'] ?? 'N/A' }}</a>
                                                <span class="degree">{{ $edu['degree'] ?? 'N/A' }}</span>
                                                <span class="year">
                                                    {{ ($edu['start_year'] ?? '') }} {{ ($edu['start_year'] && $edu['end_year']) ? '-' : '' }} {{ ($edu['end_year'] ?? '') }}
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @else
                                <li class="text-muted">No education records found.</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Experience Details -->
        <div class="col-xxl-6">
            <div class="card__wrapper">
                <div class="employee__profile-single-box p-relative">
                    <div class="card__title-wrap d-flex align-items-center justify-content-between mb-15">
                        <h5 class="card__heading-title">Experience Details</h5>
                        <a href="{{ route('employees.edit', $data->id) }}" class="edit-icon">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                    </div>
                    <div class="education__box">
                        <ul class="education__list">
                            @php
                                $experience = $data->getMeta('experience') ? json_decode($data->getMeta('experience'), true) : [];
                            @endphp
                            @if(!empty($experience) && is_array($experience))
                                @foreach($experience as $exp)
                                    <li>
                                        <div class="education__user">
                                            <div class="before__circle"></div>
                                        </div>
                                        <div class="education__content">
                                            <div class="timeline-content">
                                                <a href="#" class="name">{{ $exp['company'] ?? 'N/A' }}</a>
                                                <span class="degree">{{ $exp['position'] ?? 'N/A' }}</span>
                                                <span class="year">
                                                    {{ ($exp['start_year'] ?? '') }} {{ ($exp['start_year'] && $exp['end_year']) ? '-' : '' }} {{ ($exp['end_year'] ?? 'Present') }}
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @else
                                <li class="text-muted">No experience records found.</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Account -->
        <div class="col-xxl-4">
            <div class="card__wrapper">
                <div class="employee__profile-single-box p-relative">
                    <div class="card__title-wrap d-flex align-items-center justify-content-between mb-15">
                        <h5 class="card__heading-title">Bank Account</h5>
                        <a href="{{ route('employees.edit', $data->id) }}" class="edit-icon">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                    </div>
                    <div class="personal-info-wrapper bank__account">
                        <ul class="personal-info">
                            <li>
                                <div class="title">Account Holder Name:</div>
                                <div class="text">{{ $data->getMeta('account_holder_name') ?? 'N/A' }}</div>
                            </li>
                            <li>
                                <div class="title">Account Number:</div>
                                <div class="text">{{ $data->getMeta('account_number') ?? 'N/A' }}</div>
                            </li>
                            <li>
                                <div class="title">Bank Name:</div>
                                <div class="text">{{ $data->getMeta('bank_name') ?? 'N/A' }}</div>
                            </li>
                            <li>
                                <div class="title">Branch Name:</div>
                                <div class="text">{{ $data->getMeta('branch_name') ?? 'N/A' }}</div>
                            </li>
                            <li>
                                <div class="title">SWIFT Code:</div>
                                <div class="text">{{ $data->getMeta('swift_code') ?? 'N/A' }}</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Passport Information -->
        <div class="col-xxl-4">
            <div class="card__wrapper">
                <div class="employee__profile-single-box p-relative">
                    <div class="card__title-wrap d-flex align-items-center justify-content-between mb-15">
                        <h5 class="card__heading-title">Passport Information</h5>
                        <a href="{{ route('employees.edit', $data->id) }}" class="edit-icon">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                    </div>
                    <div class="personal-info-wrapper bank__account">
                        <ul class="personal-info">
                            <li>
                                <div class="title">Passport Number:</div>
                                <div class="text">{{ $data->getMeta('passport_number') ?? 'N/A' }}</div>
                            </li>
                            <li>
                                <div class="title">Nationality:</div>
                                <div class="text">{{ $data->getMeta('nationality') ?? 'N/A' }}</div>
                            </li>
                            <li>
                                <div class="title">Issue Date:</div>
                                <div class="text">
                                    {{ $data->getMeta('passport_issue_date') ? \Carbon\Carbon::parse($data->getMeta('passport_issue_date'))->format('d M Y') : 'N/A' }}
                                </div>
                            </li>
                            <li>
                                <div class="title">Expiry Date:</div>
                                <div class="text">
                                    {{ $data->getMeta('passport_expiry_date') ? \Carbon\Carbon::parse($data->getMeta('passport_expiry_date'))->format('d M Y') : 'N/A' }}
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Details -->
        <div class="col-xxl-4">
            <div class="card__wrapper">
                <div class="employee__profile-single-box p-relative">
                    <div class="card__title-wrap d-flex align-items-center justify-content-between mb-15">
                        <h5 class="card__heading-title">Employment Details</h5>
                        <a href="{{ route('employees.edit', $data->id) }}" class="edit-icon">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                    </div>
                    <div class="personal-info-wrapper bank__account">
                        <ul class="personal-info">
                            <li>
                                <div class="title">Department:</div>
                                <div class="text">{{ $data->department ? $data->department->name : 'N/A' }}</div>
                            </li>
                            <li>
                                <div class="title">Designation:</div>
                                <div class="text">{{ $data->getMeta('designation') ?? 'N/A' }}</div>
                            </li>
                            <li>
                                <div class="title">Salary:</div>
                                <div class="text">{{ $data->getMeta('salary') ? '$' . number_format($data->getMeta('salary')) : 'N/A' }}</div>
                            </li>
                            <li>
                                <div class="title">Shift:</div>
                                <div class="text">
                                    @if($data->getMeta('shift_id') && $data->shift)
                                        {{ $data->shift->name }} ({{ $data->shift->start_time->format('h:i A') }} - {{ $data->shift->end_time->format('h:i A') }})
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </li>
                            <li>
                                <div class="title">Status:</div>
                                <div class="text">
                                    <span class="badge badge-success">Active</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Back to List
                </a>
                <div>
                    <a href="{{ route('employees.edit', $data->id) }}" class="btn btn-primary me-2">
                        <i class="fa-solid fa-edit"></i> Edit Employee
                    </a>
                    <form method="POST" action="{{ route('employees.destroy', $data->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this employee?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i> Delete Employee
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection