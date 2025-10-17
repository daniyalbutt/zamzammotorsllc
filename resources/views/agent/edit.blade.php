@extends('layouts.app')
@section('content')
    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</li>
                    <li class="breadcrumb-item"><a href="{{ route('agents.index') }}">Agents</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Agent - {{ $agent->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-12 col-xl-12 col-lg-12">
            <div class="card__wrapper">
                <div class="card__title-wrap mb-20 row">
                    <div class="col-md-9">
                        <h5 class="card__heading-title">Edit Agent Form</h5>
                    </div>
                </div>
                <form class="form" method="post" action="{{ route('agents.update', $agent->id) }}">
                    <div class="row gx-0 g-20 gy-20 align-items-center justify-content-center">
                        @csrf
                        @method('PUT')
                        <div class="box-body">
                            @if ($errors->any())
                                {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
                            @endif
                            @if (session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                            <div class="row gy-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Name <strong>*</strong></label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', $agent->name) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">E-mail <strong>*</strong></label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ old('email', $agent->email) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">New Password</label>
                                        <input type="password" class="form-control" name="password">
                                        <small class="form-text text-muted">Leave blank to keep current password</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" name="password_confirmation">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-sharp fa-light fa-save"></i> Update Agent
                                        </button>
                                        <a href="{{ route('agents.index') }}" class="btn btn-secondary">
                                            <i class="fa-sharp fa-light fa-arrow-left"></i> Back to Agents
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Form validation
            $('form').on('submit', function(e) {
                let password = $('input[name="password"]').val();
                let confirmPassword = $('input[name="password_confirmation"]').val();
                
                if (password && confirmPassword && password !== confirmPassword) {
                    e.preventDefault();
                    alert('Password and confirm password do not match!');
                    return false;
                }
                
                if (password && password.length < 8) {
                    e.preventDefault();
                    alert('Password must be at least 8 characters long!');
                    return false;
                }
            });
        });
    </script>
@endpush
