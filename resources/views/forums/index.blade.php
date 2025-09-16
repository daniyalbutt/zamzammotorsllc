@extends('layouts.app')

@section('content')
    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Forum Discussions</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Forums</li>
                    <li class="breadcrumb-item active" aria-current="page">Forums List</li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="row">
        <div class="col-xxl-12">
            <div class="card__wrapper">
                <div class="card__title-wrap mb-20">
                    <h3 class="card__heading-title">Forums Discussions</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @foreach ($data as $item)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="card__wrapper">
                    <div class="employee__wrapper text-center">
                        <div class="employee__thumb mb-15">
                            <figure class="img-vehicle-circle mx-auto mb-10">

                                <img src="{{ asset($item->vehicle->firstImageUrl) }}" alt="image">
                            </figure>
                        </div>
                        <div class="employee__content">
                            <div class="employee__meta mb-15">
                                <h5 class="mb-8"><a href="">{{ $item->vehicle->title }}</a></h5>

                                <span class="badge bg-success"><i class="fas fa-user-tie me-1"></i> Agent:
                                    {{ $item->agent->name }}</span>
                            </div>

                            <div class="employee__btn">
                                <div class="d-flex align-items-center justify-content-center gap-15">

                                    <a href="{{ route('vehicles.show', $item->vehicle->id) }}" title="Show Vehicle"
                                        class="table__icon edit">
                                        <i class="fa-sharp fa-light fa-car"></i>
                                    </a>
                                    <a href="{{ route('forums.edit', $item->id) }}" class="table__icon download">
                                        <i class="fa-sharp fa-light fa-eye"></i>
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection
