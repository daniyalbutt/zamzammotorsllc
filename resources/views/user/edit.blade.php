@extends('layouts.app')
@section('content')
    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                    <li class="breadcrumb-item active" aria-current="page">Edit User - {{ $data->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-12 col-xl-12 col-lg-12">
            <div class="card__wrapper">
                <div class="card__title-wrap mb-20">
                    <h5 class="card__heading-title">Edit User Form</h5>
                </div>
                <form class="form" method="post" action="{{ route('users.update', $data->id) }}">
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Name <strong>*</strong></label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', $data->name) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">E-mail <strong>*</strong></label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ old('email', $data->email) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Role <strong>*</strong></label>
                                        <select name="role" id="role" class="form-control" required>
                                            @foreach ($roles as $key => $value)
                                                <option value="{{ $value->name }}"
                                                    {{ $data->getRole() == $value->name ? 'selected' : '' }}>
                                                    {{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Password</label>
                                        <input type="text" class="form-control" name="password">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
										<ul class="permission-list">

											@foreach ($permission as $key => $value)
												<li>
													<input class="form-check-input" name="permission[]"
														value="{{ $value->name }}" type="checkbox"
														id="basic_checkbox_{{ $key }}"
														{{ in_array($value->name, $userPermissions) ? 'checked' : '' }} />
													<label
														for="basic_checkbox_{{ $key }}">{{ $value->name }}</label>
												</li>
											@endforeach
										</ul>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Update User</button>
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

@push('scripts')
@endpush
