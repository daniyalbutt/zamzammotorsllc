@extends('layouts.app')
@section('content')
<div class="breadcrumb__area">
    <div class="breadcrumb__wrapper mb-25">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Body Type</li>
                <li class="breadcrumb-item active" aria-current="page">Edit Body Type - {{ $data->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-xxl-12 col-xl-12 col-lg-12">
        <div class="card__wrapper">
            <div class="card__title-wrap mb-20">
                <h5 class="card__heading-title">Edit Body Type Form</h5>
            </div>
			<form class="form" method="post" action="{{ route('body-types.update', $data->id) }}">
				<div class="row gx-0 g-20 gy-20 align-items-center justify-content-center">
		        	@csrf
					@method('PUT')
					@if($errors->any())
						{!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
					@endif
					@if(session()->has('success'))
						<div class="alert alert-success">
							{{ session()->get('success') }}
						</div>
					@endif
					<div class="row gy-3">
						<div class="col-12">
							<label class="form-label">Name <strong>*</strong></label>
							<input type="text" class="form-control" name="name" required value="{{ old('name', $data->name) }}">
						</div>
						<div class="col-12">
							<button type="submit" class="btn btn-primary">Update Body Type</button>
						</div>
					</div>
            	</div>
			</form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush