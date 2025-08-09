@extends('layouts.app')
@section('content')
<div class="breadcrumb__area">
    <div class="breadcrumb__wrapper mb-25">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Roles</li>
                <li class="breadcrumb-item active" aria-current="page">Role List</li>
            </ol>
        </nav>
    </div>
</div>


<div class="row">
    <div class="col-xxl-12">
        <div class="card__wrapper">
            <div class="card__title-wrap mb-20">
                <h3 class="card__heading-title">Role List</h3>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $value->name }}</td>
                        <td>
                            <div class="d-flex align-items-center justify-content-start gap-10">
                                @can('edit role')
                                <a href="{{ route('roles.edit', $value->id) }}" class="table__icon edit"><i class="fa-sharp fa-light fa-pen"></i></a>
                                @endcan
                                @can('delete role')
                                    <form action="{{ route('roles.destroy', $value->id) }}" method="post">
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
   <!-- <script type="text/javascript">-->
   <!-- 	$(function () {-->
   <!-- 		'use strict';-->
   <!-- 		$('#example1').DataTable({-->
		 <!-- 		'paging'      : true,-->
		 <!-- 		'lengthChange': false,-->
		 <!-- 		'searching'   : false,-->
		 <!-- 		'ordering'    : true,-->
		 <!-- 		'info'        : true,-->
		 <!-- 		'autoWidth'   : false-->
			<!--});-->
   <!-- 	});-->
   <!-- </script>-->
@endpush