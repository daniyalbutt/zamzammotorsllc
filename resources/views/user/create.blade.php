@extends('layouts.app')
@section('content')
    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                    <li class="breadcrumb-item active" aria-current="page">Add User</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-12 col-xl-12 col-lg-12">
            <div class="card__wrapper">
                <div class="card__title-wrap mb-20 row">
                    <div class="col-md-9">
                        <h5 class="card__heading-title">User Form</h5>
                    </div>


                </div>
                <form class="form" method="post" action="{{ route('users.store') }}">
                    <div class="row gx-0 g-20 gy-20 align-items-center justify-content-center">
                        @csrf
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Name <strong>*</strong></label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">E-mail <strong>*</strong></label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Role <strong>*</strong></label>
                                        <select name="roles" id="role" class="form-control select2" required
                                            multiple>
                                            <option value="">Select Role</option>
                                            @foreach ($roles as $key => $value)
                                                <option value="{{ $value->name }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Password <strong>*</strong></label>
                                        <input type="text" class="form-control" name="password" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save User</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- /.box -->
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select Role",
                allowClear: true
            });
        });
    </script>
@endpush
