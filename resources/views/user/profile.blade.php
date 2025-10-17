@extends('layouts.app')
@section('content')
    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profile Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">

        <div class="col-xxl-12">
            <div class="card__wrapper">
                <div class="card__title-wrap mb-20 row">
                    <div class="col-md-9">
                        <h5 class="card__heading-title">Profile</h5>
                    </div>
                </div>
                <form class="form" method="post">
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

                        <div class="row">

                            <div class="col-md-12 mb-4">
                                <div class="d-flex align-items-center justify-content-center align-items-center flex-column gap-10">
                                    <div class="avatar-upload">
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
                                <span class="badge bg-success">{{Auth::user()->getRole()}}</span>
                                  

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
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password"
                                    value="">
                            </div>
                             <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="password"
                                    value="">
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
