@extends('layouts.app')
@section('content')
    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('agents.index') }}">Agents</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Agent Details - {{ $agent->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-12 col-xl-12 col-lg-12">
            <div class="card__wrapper">
                <div class="card__title-wrap mb-20 row">
                    <div class="col-md-9">
                        <h5 class="card__heading-title">Agent Details</h5>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-primary">
                                <i class="fa-sharp fa-light fa-pen"></i> Edit Agent
                            </a>
                            <a href="{{ route('agents.index') }}" class="btn btn-secondary">
                                <i class="fa-sharp fa-light fa-arrow-left"></i> Back to Agents
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Agent Information</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="form-label fw-bold">Name:</label>
                                            <p class="mb-0">{{ $agent->name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="form-label fw-bold">Email:</label>
                                            <p class="mb-0">{{ $agent->email }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="form-label fw-bold">Role:</label>
                                            <p class="mb-0">
                                                <span class="badge bg-primary">{{ $agent->getRole() }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="form-label fw-bold">Created By:</label>
                                            <p class="mb-0">{{ $agent->createdByUser ? $agent->createdByUser->name : 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="form-label fw-bold">Created At:</label>
                                            <p class="mb-0">{{ $agent->created_at->format('M d, Y H:i A') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="form-label fw-bold">Last Updated:</label>
                                            <p class="mb-0">{{ $agent->updated_at->format('M d, Y H:i A') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Quick Actions</h6>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-outline-primary">
                                        <i class="fa-sharp fa-light fa-pen"></i> Edit Agent
                                    </a>
                                    <form action="{{ route('agents.destroy', $agent->id) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger w-100"
                                            onclick="return confirm('Are you sure you want to delete this agent?')">
                                            <i class="fa-regular fa-trash"></i> Delete Agent
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-body">
                                <h6 class="card-title">Statistics</h6>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <h4 class="text-primary mb-1">{{ $agent->assignedVehicles()->count() }}</h4>
                                            <small class="text-muted">Assigned Vehicles</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <h4 class="text-success mb-1">{{ $agent->agentForum()->count() }}</h4>
                                            <small class="text-muted">Forums</small>
                                        </div>
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

@push('scripts')
@endpush
