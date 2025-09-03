@extends('layouts.app')

@section('content')
    <div class="breadcrumb__area">
        <div class="breadcrumb__wrapper mb-25">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Admin Attendance</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <!-- Stats Cards -->
        <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-6">
            <div class="card__wrapper">
                <div class="d-flex align-items-center gap-sm">
                    <div class="card__icon">
                        <span><i class="fa-sharp fa-regular fa-gear"></i></span>
                    </div>
                    <div class="card__title-wrap">
                        <h6 class="card__sub-title mb-10">Total Employee</h6>
                        <div class="d-flex flex-wrap align-items-end gap-10">
                            <h3 class="card__title">{{ $totalEmployees }}</h3>
                            <span class="card__desc style_two">
                                <span class="price-increase">
                                    <i class="fa-light fa-arrow-up"></i> +5.15%
                                </span> Than Last Month
                            </span>
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
                        <h6 class="card__sub-title mb-10">Total Present</h6>
                        <div class="d-flex flex-wrap align-items-end gap-10">
                            <h3 class="card__title">{{ $presentCount }}</h3>
                            <span class="card__desc style_two">
                                <span class="price-decrease">
                                    <i class="fa-light fa-arrow-down"></i> +5.5%
                                </span> Than Last Month
                            </span>
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
                        <h6 class="card__sub-title mb-10">Total Half Day</h6>
                        <div class="d-flex flex-wrap align-items-end gap-10">
                            <h3 class="card__title">{{ $halfDayCount }}</h3>
                            <span class="card__desc style_two">
                                <span class="price-increase">
                                    <i class="fa-light fa-arrow-up"></i> +10%
                                </span> Than Last Year
                            </span>
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
                        <h6 class="card__sub-title mb-10">On Leave Employee</h6>
                        <div class="d-flex flex-wrap align-items-end gap-10">
                            <h3 class="card__title">{{ $leaveCount }}</h3>
                            <span class="card__desc style_two">
                                <span class="price-increase">
                                    <i class="fa-light fa-arrow-up"></i> +2.15%
                                </span> Than Last Month
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Date Filter -->
        <div class="col-xxl-12">
            <div class="card__wrapper mt-20">
                <div class="row g-20 gy-20 mb-20 justify-content-between align-items-end">
                    <div class="col-md-6">
                        <form action="{{ route('attendance.datewise') }}" method="GET" class="d-flex align-items-end gap-15">
                            <div class="form-group">
                                <label for="date" class="form-label">Select Date</label>
                                <input type="date" class="form-control" id="date" name="date" 
                                    value="{{ request('date', date('Y-m-d')) }}">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0 me-3">Note:</h6>
                            <div class="attendant__info-wrapper">
                                <div class="attendant__info-icon">
                                    <i class="fa fa-star text-theme"></i>
                                    <span class="attachment__info-arrow"><i class="fa fa-arrow-right text-lightest"></i></span>
                                    <h6 class="text-dark small">Holiday</h6>
                                </div>
                                <div class="attendant__info-icon">
                                    <i class="fa fa-calendar-week text-secondary"></i>
                                    <span class="attachment__info-arrow"><i class="fa fa-arrow-right text-lightest"></i></span>
                                    <h6 class="text-dark small">Day Off</h6>
                                </div>
                                <div class="attendant__info-icon">
                                    <i class="fa fa-check text-success"></i>
                                    <span class="attachment__info-arrow"><i class="fa fa-arrow-right text-lightest"></i></span>
                                    <h6 class="text-dark small">Present</h6>
                                </div>
                                <div class="attendant__info-icon">
                                    <i class="fa fa-star-half-alt text-info"></i>
                                    <span class="attachment__info-arrow"><i class="fa fa-arrow-right text-lightest"></i></span>
                                    <h6 class="text-dark small">Half Day</h6>
                                </div>
                                <div class="attendant__info-icon">
                                    <i class="fa fa-exclamation-circle text-warning"></i>
                                    <span class="attachment__info-arrow"><i class="fa fa-arrow-right text-lightest"></i></span>
                                    <h6 class="text-dark small">Late</h6>
                                </div>
                                <div class="attendant__info-icon">
                                    <i class="fa fa-times text-danger"></i>
                                    <span class="attachment__info-arrow"><i class="fa fa-arrow-right text-lightest"></i></span>
                                    <h6 class="text-dark small">Absent</h6>
                                </div>
                                <div class="attendant__info-icon">
                                    <i class="fa fa-plane-departure text-link"></i>
                                    <span class="attachment__info-arrow"><i class="fa fa-arrow-right text-lightest"></i></span>
                                    <h6 class="text-dark small">On Leave</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Attendance Table -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="attendants-table table-responsive">
                            <table class="table mb-20 multiple_tables">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Status</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Total Hours</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table__body">
                                    @foreach($attendanceData as $data)
                                        @php
                                            $user = $data['user'];
                                            $attendance = $data['attendance'];
                                            $date = $data['date'];
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="table-avatar">
                                                    <a class="employee__avatar mr-5" href="{{ route('employees.show', $user->id) }}">
                                                        <img class="img-48 border-circle" src="{{ $user->profileImage() }}" alt="User Image">
                                                    </a>
                                                    <a href="{{ route('employees.show', $user->id) }}">{{ $user->name }}</a>
                                                </span>
                                            </td>
                                            <td>
                                                @if($attendance['status'] == 'present')
                                                    <span class="badge bg-success">Present</span>
                                                @elseif($attendance['status'] == 'late')
                                                    <span class="badge bg-warning">Late</span>
                                                @elseif($attendance['status'] == 'absent')
                                                    <span class="badge bg-danger">Absent</span>
                                                @elseif($attendance['status'] == 'halfday')
                                                    <span class="badge bg-info">Half Day</span>
                                                @elseif($attendance['status'] == 'active')
                                                    <span class="badge bg-primary">Active</span>
                                                @elseif($attendance['status'] == 'weekend')
                                                    <span class="badge bg-secondary">Weekend</span>
                                                @elseif($attendance['status'] == 'today')
                                                    <span class="badge bg-info">Today</span>
                                                @elseif($attendance['status'] == 'future')
                                                    <span class="badge bg-light text-dark">Future</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $attendance['name'] }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($attendance['timein'] != '-')
                                                    {{ date('h:i A', $attendance['timein']) }}
                                                @else
                                                    {{ $attendance['timein'] }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($attendance['timeout'] != '-')
                                                    {{ date('h:i A', $attendance['timeout']) }}
                                                @else
                                                    {{ $attendance['timeout'] }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($attendance['totalhours'] != '-' && is_numeric($attendance['totalhours']))
                                                    {{ gmdate('H:i', $attendance['totalhours']) }}
                                                @else
                                                    {{ $attendance['totalhours'] }}
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary view-attendance" 
                                                    data-user="{{ $user->name }}"
                                                    data-date="{{ date('Y-m-d', $date) }}"
                                                    data-status="{{ $attendance['name'] }}"
                                                    data-timein="{{ $attendance['timein'] != '-' ? date('h:i A', $attendance['timein']) : '-' }}"
                                                    data-timeout="{{ $attendance['timeout'] != '-' ? date('h:i A', $attendance['timeout']) : '-' }}"
                                                    data-totalhours="{{ $attendance['totalhours'] != '-' && is_numeric($attendance['totalhours']) ? gmdate('H:i', $attendance['totalhours']) : '-' }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
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
    </div>

    <!-- Attendance Detail Modal -->
    <div class="modal fade" id="attendanceDetailModal" tabindex="-1" aria-labelledby="attendanceDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceDetailModalLabel">Attendance Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-4 fw-bold">Employee:</div>
                        <div class="col-8" id="modal-employee"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold">Date:</div>
                        <div class="col-8" id="modal-date"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold">Status:</div>
                        <div class="col-8" id="modal-status"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold">Time In:</div>
                        <div class="col-8" id="modal-timein"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold">Time Out:</div>
                        <div class="col-8" id="modal-timeout"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold">Total Hours:</div>
                        <div class="col-8" id="modal-totalhours"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Set default date to today if not set
            @if(!request()->has('date'))
                $('#date').val('{{ date('Y-m-d') }}');
            @endif
            
            // Handle view attendance details
            $('.view-attendance').on('click', function() {
                $('#modal-employee').text($(this).data('user'));
                $('#modal-date').text($(this).data('date'));
                $('#modal-status').text($(this).data('status'));
                $('#modal-timein').text($(this).data('timein'));
                $('#modal-timeout').text($(this).data('timeout'));
                $('#modal-totalhours').text($(this).data('totalhours'));
                
                $('#attendanceDetailModal').modal('show');
            });
            
            // Initialize DataTable if available
            if ($.fn.DataTable) {
                $('.multiple_tables').DataTable({
                    responsive: true,
                    ordering: true,
                    pageLength: 10,
                    dom: '<"top"f>rt<"bottom"lip><"clear">'
                });
            }
        });
    </script>
@endpush