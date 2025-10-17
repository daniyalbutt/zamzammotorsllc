@extends('layouts.app')
@section('content')
    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Agents</li>
                    <li class="breadcrumb-item active" aria-current="page">Agent List</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main content -->
    <div class="row">
        <div class="col-xxl-12">
            <div class="card__wrapper">
                <div class="card__title-wrap mb-20">
                    <h3 class="card__heading-title">Agent List</h3>
                   
                </div>
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif
                <table id="baseStyleToolbar" class="table table-striped">
                    <thead>
                        <tr>
                            <th>SNO</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $agent)
                            <tr class="hover-primary">
                                <td>#{{ $agent->id }}</td>
                                <td>{{ $agent->name }}</td>
                                <td>{{ $agent->email }}</td>
                                <td>
                                    <span class="badge text-sm fw-semibold rounded-pill bg-primary px-20 py-9 radius-4 text-white badge-sm">
                                        {{ $agent->getRole() }}
                                    </span>
                                </td>
                                <td>{{ $agent->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-start gap-10">
                                        <a href="{{ route('agents.show', $agent->id) }}" class="table__icon view" title="View">
                                            <i class="fa-sharp fa-light fa-eye"></i>
                                        </a>
                                        <a href="{{ route('agents.edit', $agent->id) }}" class="table__icon edit" title="Edit">
                                            <i class="fa-sharp fa-light fa-pen"></i>
                                        </a>
                                        <form action="{{ route('agents.destroy', $agent->id) }}" method="post" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="removeBtn table__icon delete" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this agent?')">
                                                <i class="fa-regular fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush