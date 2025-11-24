@extends('layouts.app')

@section('title', 'Messages - ' . $customer->user->name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-chat-dots"></i> Chat with {{ $customer->user->name }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customers.show', $customer) }}">{{ $customer->user->name }}</a></li>
                <li class="breadcrumb-item active">Messages</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="bi bi-person-circle"></i> {{ $customer->user->name }}
                        </h5>
                        <small>{{ $customer->user->email }} | {{ $customer->phone }}</small>
                    </div>
                    <div>
                        <span class="badge bg-secondary">{{ $customer->status }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Messages Area -->
            <div class="card-body" style="height: 500px; overflow-y: auto;" id="messagesContainer">
                @forelse($messages as $message)
                    <div class="mb-3 {{ $message->sender_id == auth()->id() ? 'text-end' : '' }}">
                        <div class="d-inline-block {{ $message->sender_id == auth()->id() ? 'bg-primary text-white' : 'bg-light' }} p-3 rounded" style="max-width: 70%;">
                            <div class="mb-1">
                                <strong>{{ $message->sender->name }}</strong>
                                <small class="text-muted d-block">{{ $message->created_at->format('M d, Y g:i A') }}</small>
                            </div>
                            <div>{{ $message->message }}</div>

                            @if($message->attachment)
                                <div class="mt-2">
                                    @php
                                        $extension = pathinfo($message->attachment, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                    @endphp

                                    @if($isImage)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($message->attachment) }}"
                                                 alt="Attachment"
                                                 class="img-fluid rounded"
                                                 style="max-width: 300px; max-height: 200px;">
                                        </div>
                                    @endif

                                    <a href="{{ Storage::url($message->attachment) }}"
                                       target="_blank"
                                       download
                                       class="btn btn-sm {{ $message->sender_id == auth()->id() ? 'btn-light' : 'btn-primary' }}">
                                        <i class="bi bi-paperclip"></i>
                                        {{ $isImage ? 'Download Image' : 'Download File' }}
                                        <small>({{ strtoupper($extension) }})</small>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-chat-dots" style="font-size: 3rem;"></i>
                        <p class="mt-3">No messages yet. Start the conversation!</p>
                    </div>
                @endforelse
            </div>

            <!-- Message Input -->
            <div class="card-footer">
                <form method="POST" action="{{ route('messages.store', $customer) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-2">
                        <div class="col-12">
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      name="message" rows="3" placeholder="Type your message..." required></textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <input type="file" class="form-control @error('attachment') is-invalid @enderror" 
                                   name="attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            <small class="text-muted">Max 10MB (PDF, Images, Word)</small>
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-send"></i> Send Message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-scroll to bottom of messages
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('messagesContainer');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
});
</script>
@endpush
@endsection
