@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4"><i class="bi bi-person-circle"></i> My Profile</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">
                        <i class="bi bi-key"></i> Change Password
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab">
                        <i class="bi bi-person"></i> Account Information
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">
                        <i class="bi bi-telephone"></i> Contact Information
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences" type="button" role="tab">
                        <i class="bi bi-gear"></i> Preferences
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
                        <i class="bi bi-clock-history"></i> Account Activity
                    </button>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content mt-4" id="profileTabsContent">
                <!-- Change Password Tab -->
                <div class="tab-pane fade show active" id="password" role="tabpanel">
                    <h5 class="mb-3">Change Password</h5>
                    <form action="{{ route('customer-portal.update-password') }}" method="POST" class="col-md-6">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                Current Password <span class="text-danger">*</span>
                            </label>
                            <input type="password"
                                   name="current_password"
                                   id="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                New Password <span class="text-danger">*</span>
                            </label>
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum 8 characters</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">
                                Confirm New Password <span class="text-danger">*</span>
                            </label>
                            <input type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   class="form-control"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Password
                        </button>
                    </form>
                </div>

                <!-- Account Information Tab -->
                <div class="tab-pane fade" id="account" role="tabpanel">
                    <h5 class="mb-3">Account Information</h5>
                    <form action="{{ route('customer-portal.update-account-info') }}" method="POST" class="col-md-6">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Customer Status</label>
                            <input type="text"
                                   class="form-control bg-light"
                                   value="{{ $customer->status }}"
                                   readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Member Since</label>
                            <input type="text"
                                   class="form-control bg-light"
                                   value="{{ $customer->created_at->format('M d, Y') }}"
                                   readonly>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Account Info
                        </button>
                    </form>
                </div>

                <!-- Contact Information Tab -->
                <div class="tab-pane fade" id="contact" role="tabpanel">
                    <h5 class="mb-3">Contact Information</h5>
                    <form action="{{ route('customer-portal.update-contact-info') }}" method="POST" class="col-md-6">
                        @csrf
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text"
                                   name="phone"
                                   id="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $customer->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address"
                                      id="address"
                                      class="form-control @error('address') is-invalid @enderror"
                                      rows="3">{{ old('address', $customer->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Contact Info
                        </button>
                    </form>
                </div>

                <!-- Preferences Tab -->
                <div class="tab-pane fade" id="preferences" role="tabpanel">
                    <h5 class="mb-3">Preferences</h5>
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Email Notifications</strong><br>
                            Receive email updates about your orders, reserved vehicles, and special offers.
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="emailOrders" checked>
                            <label class="form-check-label" for="emailOrders">
                                Order Updates
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="emailPromotions" checked>
                            <label class="form-check-label" for="emailPromotions">
                                Promotional Emails
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="emailNewsletter" checked>
                            <label class="form-check-label" for="emailNewsletter">
                                Newsletter
                            </label>
                        </div>

                        <button type="button" class="btn btn-primary" disabled>
                            <i class="bi bi-save"></i> Save Preferences (Coming Soon)
                        </button>
                    </div>
                </div>

                <!-- Account Activity Tab -->
                <div class="tab-pane fade" id="activity" role="tabpanel">
                    <h5 class="mb-3">Account Activity</h5>
                    <div class="col-md-8">
                        <div class="list-group">
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Account Created</h6>
                                        <small class="text-muted">{{ $customer->created_at->format('M d, Y h:i A') }}</small>
                                    </div>
                                    <span class="badge bg-success">Completed</span>
                                </div>
                            </div>

                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Last Login</h6>
                                        <small class="text-muted">{{ now()->format('M d, Y h:i A') }}</small>
                                    </div>
                                    <span class="badge bg-primary">Active</span>
                                </div>
                            </div>

                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Total Orders</h6>
                                        <small class="text-muted">{{ $customer->invoices->count() }} invoice(s)</small>
                                    </div>
                                    <span class="badge bg-info">{{ $customer->invoices->count() }}</span>
                                </div>
                            </div>

                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Assigned Agent</h6>
                                        <small class="text-muted">{{ $customer->assignedAgent->name ?? 'Not assigned' }}</small>
                                    </div>
                                    @if($customer->assignedAgent)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
