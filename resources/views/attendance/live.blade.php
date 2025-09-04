@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="breadcrumb__area">
            <div class="breadcrumb__wrapper mb-25">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Company Attendance (Live)</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card__wrapper mb-20">
            <div class="row g-20 gy-20 justify-content-between align-items-end">
                <div class="col-md-8">
                    <form id="liveAttendanceFilter" class="row g-10 align-items-end">
                        <div class="col-md-4">
                            <label for="monthSelect" class="form-label">Month</label>
                            <select class="form-select" id="monthSelect" name="month">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ (int)$month == (int)$i ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="yearSelect" class="form-label">Year</label>
                            <select class="form-select" id="yearSelect" name="year">
                                @for ($i = 2023; $i <= 2026; $i++)
                                    <option value="{{ $i }}" {{ (int)$year == (int)$i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="fas fa-search me-1"></i> Apply
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
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
                                <h6 class="text-dark small">Weekend</h6>
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
                                <i class="fa fa-clock text-primary"></i>
                                <span class="attachment__info-arrow"><i class="fa fa-arrow-right text-lightest"></i></span>
                                <h6 class="text-dark small">Active</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card__wrapper">
            <div class="table-responsive">
                <table class="table table-striped table-nowrap m-0" id="companyAttendanceCalendar">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            @for ($day = 1; $day <= $totalDaysInMonth; $day++)
                                <th class="text-center">{{ $day }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody class="table__body">
                        @foreach ($allAttendance as $row)
                            @php $user = $row['user']; $days = $row['days']; @endphp
                            <tr>
                                <td>
                                    <span class="table-avatar">
                                        <a class="employee__avatar mr-5" href="{{ route('employees.show', $user->id) }}">
                                            <img class="img-48 border-circle" src="{{ $user->profileImage() }}" alt="User Image">
                                        </a>
                                        <a href="{{ route('employees.show', $user->id) }}">{{ $user->name }}</a>
                                    </span>
                                </td>
                                @for ($day = 1; $day <= $totalDaysInMonth; $day++)
                                    @php $dayAttendance = $days[$day] ?? null; @endphp
                                    <td class="text-center">
                                        @if (!$dayAttendance)
                                            <i class="fa fa-times text-danger" title="No Record"></i>
                                        @else
                                            @if ($dayAttendance['status'] == 'present')
                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#attendance_info"
                                                   data-date="{{ date('Y-m-d', $dayAttendance['date']) }}"
                                                   data-timein="{{ $dayAttendance['timein'] != '-' ? date('h:i A', $dayAttendance['timein']) : 'N/A' }}"
                                                   data-timeout="{{ $dayAttendance['timeout'] != '-' ? date('h:i A', $dayAttendance['timeout']) : 'N/A' }}"
                                                   data-hours="{{ $dayAttendance['totalhours'] != '-' ? gmdate('H:i', $dayAttendance['totalhours']) : 'N/A' }}">
                                                    <i class="fa-solid fa-check text-success" title="Present"></i>
                                                </a>
                                            @elseif ($dayAttendance['status'] == 'late')
                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#attendance_info"
                                                   data-date="{{ date('Y-m-d', $dayAttendance['date']) }}"
                                                   data-timein="{{ $dayAttendance['timein'] != '-' ? date('h:i A', $dayAttendance['timein']) : 'N/A' }}"
                                                   data-timeout="{{ $dayAttendance['timeout'] != '-' ? date('h:i A', $dayAttendance['timeout']) : 'N/A' }}"
                                                   data-hours="{{ $dayAttendance['totalhours'] != '-' ? gmdate('H:i', $dayAttendance['totalhours']) : 'N/A' }}">
                                                    <i class="fa fa-exclamation-circle text-warning" title="Late"></i>
                                                </a>
                                            @elseif ($dayAttendance['status'] == 'early')
                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#attendance_info"
                                                   data-date="{{ date('Y-m-d', $dayAttendance['date']) }}"
                                                   data-timein="{{ $dayAttendance['timein'] != '-' ? date('h:i A', $dayAttendance['timein']) : 'N/A' }}"
                                                   data-timeout="{{ $dayAttendance['timeout'] != '-' ? date('h:i A', $dayAttendance['timeout']) : 'N/A' }}"
                                                   data-hours="{{ $dayAttendance['totalhours'] != '-' ? gmdate('H:i', $dayAttendance['totalhours']) : 'N/A' }}">
                                                    <i class="fa fa-clock text-info" title="Early Leave"></i>
                                                </a>
                                            @elseif ($dayAttendance['status'] == 'halfday' || $dayAttendance['status'] == 'nohalfday')
                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#attendance_info"
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
                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#attendance_info"
                                                   data-date="{{ date('Y-m-d', $dayAttendance['date']) }}"
                                                   data-timein="{{ $dayAttendance['timein'] != '-' ? date('h:i A', $dayAttendance['timein']) : 'N/A' }}"
                                                   data-timeout="Still Working" data-hours="In Progress">
                                                    <i class="fa fa-clock text-primary" title="Currently Active"></i>
                                                </a>
                                            @elseif ($dayAttendance['status'] == 'future')
                                                <i class="fa fa-calendar text-muted" title="Future Date"></i>
                                            @else
                                                <i class="fa fa-question text-muted" title="Unknown Status"></i>
                                            @endif
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="attendance_info" tabindex="-1" aria-labelledby="attendanceInfoLabel" aria-hidden="true">
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
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable) {
                $('#companyAttendanceCalendar').DataTable({
                    paging: true,
                    searching: true,
                    ordering: false,
                    info: true,
                    responsive: true,
                    scrollX: true,
                    pageLength: 10,
                });
            }

            $('[data-bs-toggle="modal"]').on('click', function() {
                $('#modal-date').text($(this).data('date') || '-');
                $('#modal-timein').text($(this).data('timein') || '-');
                $('#modal-timeout').text($(this).data('timeout') || '-');
                $('#modal-hours').text($(this).data('hours') || '-');
            });

            document.getElementById('liveAttendanceFilter').addEventListener('submit', function(e) {
                e.preventDefault();
                const month = document.getElementById('monthSelect').value;
                const year = document.getElementById('yearSelect').value;
                const baseUrl = "{{ route('attendance.live', ['month' => ':month', 'year' => ':year']) }}";
                window.location.href = baseUrl.replace(':month', month).replace(':year', year);
            });
        });
    </script>
@endpush


