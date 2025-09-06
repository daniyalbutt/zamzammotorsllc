@extends('layouts.app')

@section('content')
    <div class="app__slide-wrapper">
        <div class="breadcrumb__area">
            <div class="breadcrumb__wrapper mb-25">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payroll</li>
                    </ol>
                </nav>
                <div class="breadcrumb__btn">
                    <a href="{{ route('payroll.create') }}" class="btn btn-primary">Create</a>
                </div>
            </div>
        </div>
        <div class="row">
          
            <div class="col-xxl-4 col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <div class="card__wrapper">
                    <div class="d-flex align-items-center gap-sm">
                        <div class="card__icon">
                            <span><i class="fa-light fa-badge-check"></i></span>
                        </div>
                        <div class="card__title-wrap">
                            <h6 class="card__sub-title mb-10">Total Paid</h6>
                            <div class="d-flex flex-wrap align-items-end gap-10">
                                <h3 class="card__title">{{ collect($data)->where('status', 'paid')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <div class="card__wrapper">
                    <div class="d-flex align-items-center gap-sm">
                        <div class="card__icon">
                            <span><i class="fa-sharp fa-regular fa-user"></i></span>
                        </div>
                        <div class="card__title-wrap">
                            <h6 class="card__sub-title mb-10">Total Unpaid</h6>
                            <div class="d-flex flex-wrap align-items-end gap-10">
                                <h3 class="card__title">{{ collect($data)->where('status', 'unpaid')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <div class="card__wrapper">
                    <div class="d-flex align-items-center gap-sm">
                        <div class="card__icon">
                            <span><i class="fa-sharp fa-regular fa-house-person-leave"></i></span>
                        </div>
                        <div class="card__title-wrap">
                            <h6 class="card__sub-title mb-10">Total Payslips</h6>
                            <div class="d-flex flex-wrap align-items-end gap-10">
                                <h3 class="card__title">{{ collect($data)->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-12">
                <div class="card__wrapper">
                    <div class="table__wrapper table-responsive">
                        <table class="table hover mb-20" id="dataTableDefualt">
                            <thead>
                                <tr class="table__title">
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Designation</th>
                                    <th>Email</th>
                                    <th>Joining Date</th>
                                    <th>Salary (Monthly)</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="table__body">
                                @foreach ($data as $item)
                                    <tr>

                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->user->getMeta('designation') }}</td>
                                        <td>{{ $item->user->email }}</td>
                                        <td>{{ $item->user->getMeta('date_of_joining') }}</td>
                                        <td>{{ $item->user->getMeta('salary') }}</td>
                                        <td><span class="badge bg-{{ $item->status_badge }}">{{strtoupper($item->status)}}</span></td>


                                        <td class="table__icon-box">
                                            <div class="d-flex align-items-center justify-content-start gap-10">
                                                <a href="{{ route('payroll.show', $item->id) }}"
                                                    class="table__icon download">
                                                    <i class="fa-regular fa-eye"></i>
                                                </a>
                                                <a href="{{ route('payroll.edit', $item->id) }}" class="table__icon edit"><i
                                                        class="fa-sharp fa-light fa-pen"></i></a>
                                                <button class="removeBtn table__icon delete"><i
                                                        class="fa-regular fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
