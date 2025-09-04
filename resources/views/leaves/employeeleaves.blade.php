@extends('layouts.app')

@section('content')
    <div class="app__slide-wrapper">
        <div class="breadcrumb__area">
            <div class="breadcrumb__wrapper mb-25">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Employee Leave</li>
                    </ol>
                </nav>
                <div class="breadcrumb__btn">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewLeave">Add
                        Leave</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <div class="card__wrapper">
                    <div class="d-flex align-items-center gap-sm">
                        <div class="card__icon">
                            <span><i class="fa-light fa-ban"></i></span>
                        </div>
                        <div class="card__title-wrap">
                            <h6 class="card__sub-title mb-10">Total Leave</h6>
                            <div class="d-flex flex-wrap align-items-end gap-10">
                                <h3 class="card__title">{{ $leaves->count() }}</h3>
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
                            <h6 class="card__sub-title mb-10">Approve</h6>
                            <div class="d-flex flex-wrap align-items-end gap-10">
                                <h3 class="card__title">{{ collect($leaves)->where('status', 'approved')->count() }}</h3>
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
                            <h6 class="card__sub-title mb-10">Rejected </h6>
                            <div class="d-flex flex-wrap align-items-end gap-10">
                                <h3 class="card__title">{{ collect($leaves)->where('status', 'rejected')->count() }}</h3>
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
                            <h6 class="card__sub-title mb-10">Pending</h6>
                            <div class="d-flex flex-wrap align-items-end gap-10">
                                <h3 class="card__title">{{ collect($leaves)->where('status', 'pending')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-12">
                <div class="card__wrapper">
                    @session('success')
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endsession
                    <div class="table__wrapper table-responsive">
                        <table class="table mb-20" id="dataTableDefualt">
                            <thead>
                                <tr class="table__title">
                                    <th>Leave Type</th>
                                    <th>Date</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    @can('action leaves')
                                        <th>Action</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody class="table__body">
                                @foreach ($leaves as $item)
                                    <tr class="odd">
                                        <td class="table__leave-type sorting_1">{{ ucwords($item->type) }}</td>
                                        <td class="table__leave-duration">{{ date('d, F Y', $item->date) }}</td>
                                        <td class="table__leave-rason">{{ $item->reason ?? 'Reason Not defined' }}</td>
                                        <td><span
                                                class="bd-badge bg-{{ $item->badge }}">{{ ucwords($item->status) }}</span>
                                        </td>
                                        @can('action leaves')
                                            <td class="table__icon-box">
                                                <form id="statusForm" action="{{ route('leave.updateStatus', $leave->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" id="statusInput">

                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Change Status
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                                                            <li><a class="dropdown-item" href="#"
                                                                    onclick="submitStatus('pending')">Pending</a></li>
                                                            <li><a class="dropdown-item" href="#"
                                                                    onclick="submitStatus('approved')">Approved</a></li>
                                                            <li><a class="dropdown-item" href="#"
                                                                    onclick="submitStatus('rejected')">Rejected</a></li>
                                                        </ul>
                                                    </div>
                                                </form>
                                            </td>
                                        @endcan
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

@push('js')
    <div id="addNewLeave" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Leave Request</h5>
                    <button type="button" class="bd-btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-xmark-large"></i></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('attendance.applyLeave') }}" method="POST">
                        @csrf
                        <div class="row gy-20">
                            <div class="col-xl-12">
                                <div class="card__wrapper mb-20">
                                    <div class="row gy-20">
                                        <div class="col-md-12">
                                            <div class="from__input-box">
                                                <div class="form__input-title">
                                                    <label for="largeSelect" class="form-label">Leave
                                                        Type<span>*</span></label>
                                                </div>
                                                <div class="form__input">
                                                    <select id="leave_type" name="leave_type" class="form-select">
                                                        <option disabled selected hidden>Select Leave Type</option>
                                                        <option value="medical leave">Medical Leave</option>
                                                        <option value="personal leave">Personal Leave</option>
                                                        <option value="casual leave">Casual Leave</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="from__input-box">
                                                <div class="form__input-title">
                                                    <label class="form-check-label" for="dateDuration">Leave
                                                        Date <span>*</span></label>
                                                </div>
                                                <div class="form__input">
                                                    <input type="date" name="date" class="form-control" required
                                                        min="{{ date('Y-m-d') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="from__input-box">
                                                <div class="form__input-title">
                                                    <label>Reason</label>
                                                </div>
                                                <div class="form__input">
                                                    <textarea class="form-control" name="reason "></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="submit__btn text-center">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush
