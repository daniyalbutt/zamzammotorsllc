@extends('layouts.app')
@section('content')
<div class="breadcrumb__area">
    <div class="breadcrumb__wrapper mb-25">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Users</li>
                <li class="breadcrumb-item active" aria-current="page">User List</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Main content -->
<div class="row">
    <div class="col-xxl-12">
        <div class="card__wrapper">
            <div class="card__title-wrap mb-20">
                <h3 class="card__heading-title">User List</h3>
            </div>
            @if(session()->has('success'))
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $value)
                    <tr class="hover-primary">
                        <td>#{{ $value->id }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->email }}</td>
                        <td><span class="badge text-sm fw-semibold rounded-pill bg-primary-600 px-20 py-9 radius-4 text-white badge-sm">{{ $value->getRole(); }}</span></span></td>
                        <td>
                            <div class="d-flex align-items-center justify-content-start gap-10">
                                @can('edit user')
                                <a href="{{ route('users.edit', $value->id) }}" class="table__icon edit"><i class="fa-sharp fa-light fa-pen"></i></a>
                                @endcan
                                @can('delete user')
                                <form action="{{ route('users.destroy', $value->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="removeBtn table__icon delete"><i class="fa-regular fa-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush