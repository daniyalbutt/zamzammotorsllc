@extends('layouts.app')

@section('title', $customer->user->name)

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="bi bi-person-circle"></i> {{ $customer->user->name }}</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('messages.index', $customer) }}" class="btn btn-primary">
            <i class="bi bi-chat-dots"></i> Messages
        </a>
        @can('edit customers')
            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
        @endcan
        @can('delete customers')
            <form method="POST" action="{{ route('customers.destroy', $customer) }}"
                  onsubmit="return confirm('Delete this customer?');" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </form>
        @endcan
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <!-- Customer Info -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Contact Information</h5>
            </div>
            <div class="card-body">
                <dl class="mb-0">
                    <dt>Email:</dt>
                    <dd>{{ $customer->user->email }}</dd>

                    <dt>Phone:</dt>
                    <dd>{{ $customer->phone }}</dd>

                    @if($customer->address)
                        <dt>Address:</dt>
                        <dd>{{ $customer->address }}</dd>
                    @endif

                    <dt>Lead Source:</dt>
                    <dd><span class="badge bg-secondary">{{ $customer->lead_source }}</span></dd>

                    <dt>Status:</dt>
                    <dd>
                        @if($customer->status == 'Follow-up')
                            <span class="badge bg-warning">Follow-up</span>
                        @elseif($customer->status == 'In Negotiation')
                            <span class="badge bg-info">In Negotiation</span>
                        @else
                            <span class="badge bg-success">Closed</span>
                        @endif
                    </dd>

                    <dt>Assigned Agent:</dt>
                    <dd class="mb-0">{{ $customer->assignedAgent->name ?? 'Unassigned' }}</dd>
                </dl>
            </div>
        </div>

        <!-- Add Note -->
        @can('create customer notes')
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-sticky"></i> Add Note</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('customer-notes.store', $customer) }}">
                    @csrf
                    <div class="mb-3">
                        <textarea class="form-control" name="note" rows="3" required placeholder="Add a note..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-plus-circle"></i> Add Note
                    </button>
                </form>
            </div>
        </div>
        @endcan
    </div>

    <div class="col-lg-8">
        <!-- Customer Notes -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-stickies"></i> Notes</h5>
            </div>
            <div class="card-body">
                @if($customer->notes->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($customer->notes as $note)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <p class="mb-1">{{ $note->note }}</p>
                                    <small class="text-muted">
                                        By {{ $note->createdBy->name }} - {{ $note->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                @can('delete customer notes')
                                    <form method="POST" action="{{ route('customer-notes.destroy', $note) }}" 
                                          onsubmit="return confirm('Delete this note?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No notes yet.</p>
                @endif
            </div>
        </div>

        <!-- Invoices -->
        <div class="card">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-receipt"></i> Invoices</h5>
                @can('create invoices')
                    <a href="{{ route('invoices.create', ['customer_id' => $customer->id]) }}" class="btn btn-sm btn-light">
                        <i class="bi bi-plus-circle"></i> New Invoice
                    </a>
                @endcan
            </div>
            <div class="card-body">
                @if($customer->invoices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Invoice#</th>
                                    <th>Vehicle</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->vehicle->title }}</td>
                                    <td>${{ number_format($invoice->vehicle_price, 2) }}</td>
                                    <td>
                                        @if($invoice->status == 'Paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($invoice->status == 'Partial')
                                            <span class="badge bg-warning">Partial</span>
                                        @else
                                            <span class="badge bg-secondary">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No invoices yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
