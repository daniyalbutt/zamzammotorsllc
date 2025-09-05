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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#createNew">Create</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <div class="card__wrapper">
                    <div class="d-flex align-items-center gap-sm">
                        <div class="card__icon">
                            <span><i class="fa-sharp fa-regular fa-gear"></i></span>
                        </div>
                        <div class="card__title-wrap">
                            <h6 class="card__sub-title mb-10">Total Employee</h6>
                            <div class="d-flex flex-wrap align-items-end gap-10">
                                <h3 class="card__title">8450</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <div class="card__wrapper">
                    <div class="d-flex align-items-center gap-sm">
                        <div class="card__icon">
                            <span><i class="fa-light fa-badge-check"></i></span>
                        </div>
                        <div class="card__title-wrap">
                            <h6 class="card__sub-title mb-10">Total Paid</h6>
                            <div class="d-flex flex-wrap align-items-end gap-10">
                                <h3 class="card__title">150</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <div class="card__wrapper">
                    <div class="d-flex align-items-center gap-sm">
                        <div class="card__icon">
                            <span><i class="fa-sharp fa-regular fa-user"></i></span>
                        </div>
                        <div class="card__title-wrap">
                            <h6 class="card__sub-title mb-10">Total Unpaid</h6>
                            <div class="d-flex flex-wrap align-items-end gap-10">
                                <h3 class="card__title">3130</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <div class="card__wrapper">
                    <div class="d-flex align-items-center gap-sm">
                        <div class="card__icon">
                            <span><i class="fa-sharp fa-regular fa-house-person-leave"></i></span>
                        </div>
                        <div class="card__title-wrap">
                            <h6 class="card__sub-title mb-10">Total Leave</h6>
                            <div class="d-flex flex-wrap align-items-end gap-10">
                                <h3 class="card__title">55</h3>
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
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
