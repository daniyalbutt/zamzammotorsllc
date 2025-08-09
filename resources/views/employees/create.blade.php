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

    <div class="row">
        <div class="col-xxl-12 col-xl-12 col-lg-12">
            <div class="card__wrapper">
                <div class="card__title-wrap mb-20">
                    <h5 class="card__heading-title">Employees Form</h5>
                </div>
                <form class="form" method="post" action="{{ $data ? route('employees.update', $data->id) : route('employees.store') }}">
                    @if($data)
                        @method('PUT')
                    @endif
                    <div class="row gx-0 g-20 gy-20 align-items-center justify-content-center">
                        @csrf
                        @if ($errors->any())
                            {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
                        @endif
                        @if (session()->has('success'))
                            <div class="alert alert-success">
                                {{ session()->get('success') }}
                            </div>
                        @endif
                        <div class="col-12 col-md-6">
                            <label class="form-label">Name <strong>*</strong></label>
                            <input type="text" class="form-control" name="name" value="{{ $data ? $data->name : old('name') }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Email <strong>*</strong></label>
                            <input type="email" class="form-control" name="email" value="{{ $data ? $data->email : old('email') }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Designation <strong>*</strong></label>
                            <input type="text" class="form-control" name="meta[designation]" value="{{ $data ? $data->getMeta('designation') : old('meta[designation]') }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="meta[phone]" value="{{ $data ? $data->getMeta('phone') : old('meta[phone]') }}">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Date of Joining</label>
                            <input type="date" class="form-control" name="meta[date_of_joining]"
                            value="{{ $data ? $data->getMeta('date_of_joining') : old('meta[date_of_joining]') }}">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Department <strong>*</strong></label>
                            <select class="form-control" name="meta[department_id]" required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option {{ $data ? ($data->getMeta('department_id') == $department->id ? 'selected' : '') : ''  }} value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="meta[address]" value="{{ $data ? $data->getMeta('address') : old('meta[address]') }}">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Salary <strong>*</strong></label>
                            <input type="number" class="form-control" name="meta[salary]" value="{{ $data ? $data->getMeta('salary') : old('meta[salary]') }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Shift Timings <strong>*</strong></label>
                            <select class="form-control" name="meta[shift_id]" required>
                                <option value="">Select Shift</option>
                                @foreach ($shifts as $shift)
                                    <option {{ $data ? ($data->getMeta('shift_id') == $shift->id ? 'selected' : '') : ''  }} value="{{ $shift->id }}">{{ $shift->name }} -- {{ $shift->start_time->format('h:i A') }} - {{ $shift->end_time->format('h:i A')  }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Password {{ !$data ? '<strong>*</strong>' : '' }}</label>
                            <input type="password" class="form-control" name="password" 
                                {{ !$data ? 'required' : '' }} 
                                placeholder="{{ $data ? 'Leave blank to keep current password' : '' }}">
                        </div>


                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save Employees</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush
