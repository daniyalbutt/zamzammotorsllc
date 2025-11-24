@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="bi bi-megaphone"></i> Announcements</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('announcements.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Announcement
        </a>
    </div>
</div>

<div class="row">
    @forelse($announcements as $announcement)
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <strong>{{ $announcement->title }}</strong>
                @if($announcement->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </div>
            <div class="card-body">
                <p>{{ $announcement->content }}</p>
                <small class="text-muted">Posted by {{ $announcement->creator->name }} - {{ $announcement->created_at->diffForHumans() }}</small>
            </div>
            <div class="card-footer">
                <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No announcements yet.
        </div>
    </div>
    @endforelse
</div>
@endsection
