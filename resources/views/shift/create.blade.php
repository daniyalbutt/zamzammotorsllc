@extends('layouts.app')
@section('content')
<div class="breadcrumb__area">
    <div class="breadcrumb__wrapper mb-25">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Shift</a></li>
                <li class="breadcrumb-item active" aria-current="page">Shift</li>
                <li class="breadcrumb-item active" aria-current="page">Add Role</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-xxl-12 col-xl-12 col-lg-12">
        <div class="card__wrapper">
            <div class="card__title-wrap mb-20">
                <h5 class="card__heading-title">Shift Form</h5>
            </div>
            <form class="form" method="post" action="{{ route('shifts.store') }}">
                <div class="row gx-0 g-20 gy-20 align-items-center justify-content-center">
					@csrf
					@if($errors->any())
						{!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
					@endif
					@if(session()->has('success'))
						<div class="alert alert-success">
							{{ session()->get('success') }}
						</div>
					@endif
					
                    <div class="col-12">
                        <label class="form-label">Name <strong>*</strong></label>
                        <input type="text" class="form-control" value="{{ $data ? $data->name : '' }}" name="name" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Start Shift <strong>*</strong></label>
                        <input type="time" class="form-control" value="{{ $data ? $data->start_time : '' }}" name="start_time" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">End Shift <strong>*</strong></label>
                        <input type="time" class="form-control" value="{{ $data ? $data->end_time : '' }}" name="end_time" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Save Role</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')

@endpush