@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Breadcrumb Section -->
        <div class="breadcrumb__area">
            <div class="breadcrumb__wrapper mb-25">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="" class="text-decoration-none">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Employee Attendance</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Stats Cards Row -->
        <div class="row mb-4">
            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <div class="card__wrapper">
                    <div class="d-flex align-items-center gap-sm">
                        <div class="card__icon">
                            <span><i class="fa-sharp fa-regular fa-calendar"></i></span>
                        </div>
                        <div class="card__title-wrap">
                            <h6 class="card__sub-title mb-10">Total Present Days</h6>
                            <div class="card__content">
                                <h3 class="card__title mb-5">
                                    {{ collect($attendance)->whereIn('status', ['present', 'late', 'early'])->count() }}
                                </h3>
                                <span class="card__desc style_two">
                                    <span class="price-increase">
                                        <i class="fa-light fa-arrow-up"></i>
                                        {{ number_format((collect($attendance)->whereIn('status', ['present', 'late', 'early'])->count() /cal_days_in_month(CAL_GREGORIAN, $month, $year)) *100,1) }}%
                                    </span> This Month
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
                            <span><i class="fa-sharp fa-regular fa-clock"></i></span>
                        </div>
                        <div class="card__title-wrap">
                            <h6 class="card__sub-title mb-10">Total Work Time</h6>
                            <div class="card__content">
                                <h3 class="card__title mb-5">{{ gmdate('H', $totalhours) }} Hrs :
                                    {{ gmdate('i', $totalhours) }} Min</h3>
                                <span class="card__desc style_two">
                                    <span class="price-increase"><i class="fa-light fa-arrow-up"></i> +5.15%</span>
                                    Than Last Month
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
                            <h6 class="card__sub-title mb-10">Half Days</h6>
                            <div class="card__content">
                                <h3 class="card__title mb-5">{{ collect($attendance)->where('status', 'halfday')->count() }}
                                    Days</h3>
                                <span class="card__desc style_two">
                                    <span class="price-decrease"><i class="fa-light fa-arrow-down"></i> -1.5%</span>
                                    Than Last Month
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
                            <h6 class="card__sub-title mb-10">Total Absent</h6>
                            <div class="card__content">
                                <h3 class="card__title mb-5">{{ collect($attendance)->where('status', 'absent')->count() }}
                                    Days
                                </h3>
                                <span class="card__desc style_two">
                                    <span class="price-increase"><i class="fa-light fa-arrow-up"></i> +2.15%</span>
                                    Than Last Month
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        @role('hr')
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Attendance</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <form id="attendanceForm">
                            <input type="hidden" name="userid" value="{{ $user->id }}">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="monthSelect" class="form-label">Month</label>
                                    <select class="form-select" name="month" id="monthSelect">
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                {{ request('month', $month) == $i ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="yearSelect" class="form-label">Year</label>
                                    <select class="form-select" name="year" id="yearSelect">
                                        @for ($i = 2023; $i <= 2026; $i++)
                                            <option value="{{ $i }}"
                                                {{ request('year', $year) == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3 d-flex align-items-center">
                                    <button type="submit" class="btn btn-primary mt-3">
                                        <i class="fas fa-search me-1"></i> Search
                                    </button>
                                </div>

                                <div class="col-md-3 mb-3 d-flex align-items-center">
                                    <div class="card bg-light border-0 w-100">
                                        <div class="card-body text-center py-2">
                                            <small class="text-muted d-block">
                                                Month: {{ date('F Y', mktime(0, 0, 0, $month, 10)) }}
                                            </small>
                                            <h5 class="mb-0 text-primary">
                                                {{ number_format($totalhours / 3600, 1) }}h Total
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        @endrole

        <!-- Main Attendance Table -->
        <div class="col-xxl-12">
            <div class="card__wrapper">
                <!-- Legend/Note Section -->
                <div class="row g-20 gy-20 mb-20 justify-content-between align-items-end">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center">
                            <h6 class="">Note:</h6>
                            <div class="attendant__info-wrapper">
                                <div class="attendant__info-icon">
                                    <i class="fa fa-star text-theme"></i>
                                    <span class="attachment__info-arrow"><i
                                            class="fa fa-arrow-right text-lightest"></i></span>
                                    <h6 class="text-dark small">Holiday</h6>
                                </div>
                                <div class="attendant__info-icon">
                                    <i class="fa fa-calendar-week text-secondary"></i>
                                    <span class="attachment__info-arrow"><i
                                            class="fa fa-arrow-right text-lightest"></i></span>
                                    <h6 class="text-dark small">Weekend</h6>
                                </div>
                                <div class="attendant__info-icon">
                                    <i class="fa fa-check text-success"></i>
                                    <span class="attachment__info-arrow"><i
                                            class="fa fa-arrow-right text-lightest"></i></span>
                                    <h6 class="text-dark small">Present</h6>
                                </div>
                                <div class="attendant__info-icon">
                                    <i class="fa fa-star-half-alt text-info"></i>
                                    <span class="attachment__info-arrow"><i
                                            class="fa fa-arrow-right text-lightest"></i></span>
                                    <h6 class="text-dark small">Half Day</h6>
                                </div>
                                <div class="attendant__info-icon">
                                    <i class="fa fa-exclamation-circle text-warning"></i>
                                    <span class="attachment__info-arrow"><i
                                            class="fa fa-arrow-right text-lightest"></i></span>
                                    <h6 class="text-dark small">Late</h6>
                                </div>
                                <div class="attendant__info-icon">
                                    <i class="fa fa-times text-danger"></i>
                                    <span class="attachment__info-arrow"><i
                                            class="fa fa-arrow-right text-lightest"></i></span>
                                    <h6 class="text-dark small">Absent</h6>
                                </div>
                                <div class="attendant__info-icon">
                                    <i class="fa fa-clock text-primary"></i>
                                    <span class="attachment__info-arrow"><i
                                            class="fa fa-arrow-right text-lightest"></i></span>
                                    <h6 class="text-dark small">Active</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendar Style Attendance Table -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-nowrap mb-0" id="attendanceCalendar">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        @for ($day = 1; $day <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $day++)
                                            <th class="text-center">{{ $day }}</th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody class="table__body">
                                    <tr>
                                        <td>
                                            <span class="table-avatar">
                                                <a class="employee__avatar mr-5" href="#"><img
                                                        class="img-48 border-circle" src="{{ $user->profileImage() }}"
                                                        alt="User Image"></a>
                                                <a href="#">{{ $user->name ?? 'Employee' }}</a>
                                            </span>
                                        </td>

                                        @php
                                            $attendanceByDay = collect($attendance)->keyBy(function ($item) {
                                                return date('j', $item['date']);
                                            });
                                            $joiningDate = strtotime($user->getMeta('date_of_joining'));
                                        @endphp

                                        @for ($day = 1; $day <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $day++)
                                            @php
                                                $dayAttendance = $attendanceByDay->get($day);
                                                $dayTimestamp = strtotime("$year-$month-$day");
                                                $isBeforeJoining = $dayTimestamp < $joiningDate;
                                            @endphp

                                            <td class="text-center">
                                                @if ($isBeforeJoining)
                                                    <i class="fa fa-minus text-muted" title="Before Joining"></i>
                                                @elseif ($dayAttendance)
                                                    @if ($dayAttendance['status'] == 'present')
                                                        <a href="javascript:void(0);" data-bs-toggle="modal"
                                                            data-bs-target="#attendance_info"
                                                            data-date="{{ date('Y-m-d', $dayAttendance['date']) }}"
                                                            data-timein="{{ $dayAttendance['timein'] != '-' ? date('h:i A', $dayAttendance['timein']) : 'N/A' }}"
                                                            data-timeout="{{ $dayAttendance['timeout'] != '-' ? date('h:i A', $dayAttendance['timeout']) : 'N/A' }}"
                                                            data-hours="{{ $dayAttendance['totalhours'] != '-' ? gmdate('H:i', $dayAttendance['totalhours']) : 'N/A' }}">
                                                            <i class="fa-solid fa-check text-success" title="Present"></i>
                                                        </a>
                                                    @elseif ($dayAttendance['status'] == 'late')
                                                        <a href="javascript:void(0);" data-bs-toggle="modal"
                                                            data-bs-target="#attendance_info"
                                                            data-date="{{ date('Y-m-d', $dayAttendance['date']) }}"
                                                            data-timein="{{ $dayAttendance['timein'] != '-' ? date('h:i A', $dayAttendance['timein']) : 'N/A' }}"
                                                            data-timeout="{{ $dayAttendance['timeout'] != '-' ? date('h:i A', $dayAttendance['timeout']) : 'N/A' }}"
                                                            data-hours="{{ $dayAttendance['totalhours'] != '-' ? gmdate('H:i', $dayAttendance['totalhours']) : 'N/A' }}">
                                                            <i class="fa fa-exclamation-circle text-warning"
                                                                title="Late"></i>
                                                        </a>
                                                    @elseif ($dayAttendance['status'] == 'early')
                                                        <a href="javascript:void(0);" data-bs-toggle="modal"
                                                            data-bs-target="#attendance_info"
                                                            data-date="{{ date('Y-m-d', $dayAttendance['date']) }}"
                                                            data-timein="{{ $dayAttendance['timein'] != '-' ? date('h:i A', $dayAttendance['timein']) : 'N/A' }}"
                                                            data-timeout="{{ $dayAttendance['timeout'] != '-' ? date('h:i A', $dayAttendance['timeout']) : 'N/A' }}"
                                                            data-hours="{{ $dayAttendance['totalhours'] != '-' ? gmdate('H:i', $dayAttendance['totalhours']) : 'N/A' }}">
                                                            <i class="fa fa-clock text-info" title="Early Leave"></i>
                                                        </a>
                                                    @elseif ($dayAttendance['status'] == 'halfday' || $dayAttendance['status'] == 'nohalfday')
                                                        <a href="javascript:void(0);" data-bs-toggle="modal"
                                                            data-bs-target="#attendance_info"
                                                            data-date="{{ date('Y-m-d', $dayAttendance['date']) }}"
                                                            data-timein="{{ $dayAttendance['timein'] != '-' ? date('h:i A', $dayAttendance['timein']) : 'N/A' }}"
                                                            data-timeout="{{ $dayAttendance['timeout'] != '-' ? date('h:i A', $dayAttendance['timeout']) : 'N/A' }}"
                                                            data-hours="{{ $dayAttendance['totalhours'] != '-' ? gmdate('H:i', $dayAttendance['totalhours']) : 'N/A' }}">
                                                            <i class="fa fa-star-half-alt text-info" title="Half Day"></i>
                                                        </a>
                                                    @elseif ($dayAttendance['status'] == 'absent')
                                                        <i class="fa fa-times text-danger" title="Absent"></i>
                                                    @elseif ($dayAttendance['status'] == 'weekend')
                                                        <i class="fa fa-calendar-week text-secondary" title="Weekend"></i>
                                                    @elseif ($dayAttendance['status'] == 'active' || $dayAttendance['status'] == 'today')
                                                        <a href="javascript:void(0);" data-bs-toggle="modal"
                                                            data-bs-target="#attendance_info"
                                                            data-date="{{ date('Y-m-d', $dayAttendance['date']) }}"
                                                            data-timein="{{ $dayAttendance['timein'] != '-' ? date('h:i A', $dayAttendance['timein']) : 'N/A' }}"
                                                            data-timeout="Still Working" data-hours="In Progress">
                                                            <i class="fa fa-clock text-primary"
                                                                title="Currently Active"></i>
                                                        </a>
                                                    @elseif ($dayAttendance['status'] == 'future')
                                                        <i class="fa fa-calendar text-muted" title="Future Date"></i>
                                                    @else
                                                        <i class="fa fa-question text-muted" title="Unknown Status"></i>
                                                    @endif
                                                @else
                                                    <i class="fa fa-times text-danger" title="No Record"></i>
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mt-4">
            <div class="col-lg-4 mb-4">
                <div class="card border-left-success shadow h-100">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-success">Working Days Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-8">Present Days</div>
                            <div class="col-4 text-end">
                                <span
                                    class="badge bg-success">{{ collect($attendance)->whereIn('status', ['present', 'late', 'early'])->count() }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-8">Half Days</div>
                            <div class="col-4 text-end">
                                <span
                                    class="badge bg-warning">{{ collect($attendance)->filter(function ($attend) {
                                            return $attend['status'] === 'halfday' || $attend['status'] === 'nohalfday';
                                        })->count() }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-8">Absent Days</div>
                            <div class="col-4 text-end">
                                <span
                                    class="badge bg-danger">{{ collect($attendance)->where('status', 'absent')->count() }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-8">Weekends</div>
                            <div class="col-4 text-end">
                                <span
                                    class="badge bg-info">{{ collect($attendance)->where('status', 'weekend')->count() }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">Late Days</div>
                            <div class="col-4 text-end">
                                <span
                                    class="badge bg-warning">{{ collect($attendance)->where('status', 'late')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Time Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-8">Total Hours</div>
                            <div class="col-4 text-end">
                                <span class="badge bg-primary">{{ number_format($totalhours / 3600, 1) }}h</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-8">Average/Day</div>
                            <div class="col-4 text-end">
                                @php
                                    $workingDays = collect($attendance)
                                        ->whereIn('status', ['present', 'halfday', 'late', 'early'])
                                        ->count();
                                    $avgHours = $workingDays > 0 ? $totalhours / 3600 / $workingDays : 0;
                                @endphp
                                <span class="badge bg-primary">{{ number_format($avgHours, 1) }}h</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-8">Working Days</div>
                            <div class="col-4 text-end">
                                <span class="badge bg-success">{{ $workingDays }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card border-left-info shadow h-100">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-info">Month Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-8">Month</div>
                            <div class="col-4 text-end">
                                <span class="badge bg-info">{{ date('F', mktime(0, 0, 0, $month, 10)) }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-8">Year</div>
                            <div class="col-4 text-end">
                                <span class="badge bg-info">{{ $year }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-8">Total Days</div>
                            <div class="col-4 text-end">
                                <span class="badge bg-info">{{ cal_days_in_month(CAL_GREGORIAN, $month, $year) }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">Attendance Rate</div>
                            <div class="col-4 text-end">
                                @php
                                    $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                                    $presentDays = collect($attendance)
                                        ->whereIn('status', ['present', 'halfday', 'late', 'early'])
                                        ->count();
                                    $rate = $totalDays > 0 ? ($presentDays / $totalDays) * 100 : 0;
                                @endphp
                                <span class="badge bg-success">{{ number_format($rate, 1) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Info Modal -->
    <div class="modal fade" id="attendance_info" tabindex="-1" aria-labelledby="attendanceInfoLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceInfoLabel">Attendance Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6"><strong>Date:</strong></div>
                        <div class="col-6" id="modal-date">-</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6"><strong>Time In:</strong></div>
                        <div class="col-6" id="modal-timein">-</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6"><strong>Time Out:</strong></div>
                        <div class="col-6" id="modal-timeout">-</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6"><strong>Total Hours:</strong></div>
                        <div class="col-6" id="modal-hours">-</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Filter attendance
            $('.filter-btn').on('click', function() {
                var month = $('.month-select').val();
                var year = $('.year-select').val();
                var url = "{{ route('attendance.show', ['month' => ':month', 'year' => ':year']) }}";
                url = url.replace(':month', month);
                url = url.replace(':year', year);
                window.location.href = url;
            });

            // Export CSV
            $('.csv-btn').on('click', function() {
                var month = $('.month-select').val();
                var year = $('.year-select').val();
                // Add your CSV export logic here
                alert('CSV export functionality to be implemented');
            });

            // Handle modal data
            $('[data-bs-toggle="modal"]').on('click', function() {
                var date = $(this).data('date');
                var timein = $(this).data('timein');
                var timeout = $(this).data('timeout');
                var hours = $(this).data('hours');

                $('#modal-date').text(date || '-');
                $('#modal-timein').text(timein || '-');
                $('#modal-timeout').text(timeout || '-');
                $('#modal-hours').text(hours || '-');
            });

            // Initialize DataTable if needed
            if ($.fn.DataTable) {
                $('#attendanceCalendar').DataTable({
                    "paging": false,
                    "searching": false,
                    "ordering": false,
                    "info": false,
                    "responsive": true,
                    "scrollX": true
                });
            }
        });

        document.getElementById('attendanceForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let month = document.getElementById('monthSelect').value;
            let year = document.getElementById('yearSelect').value;
            let userid = document.querySelector('[name="userid"]').value;

            let baseUrl = "{{ url('get-attendance') }}";
            let newUrl = `${baseUrl}/${month}/${year}/${userid}`;
            
            window.location.href = newUrl;
        });
    </script>
@endpush
