@extends('layouts.app')
@section('content')
    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Department</li>
                    <li class="breadcrumb-item active" aria-current="page">Add Department</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-12 col-xl-12 col-lg-12">
            <div class="card__wrapper">
                <div class="card__title-wrap mb-20">
                    <h5 class="card__heading-title">Create Department</h5>
                </div>
                <form class="form" method="post" action="{{ route('departments.store') }}">
                    <div class="row gx-0 g-20 gy-20 align-items-center">
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



                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save Department</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush
