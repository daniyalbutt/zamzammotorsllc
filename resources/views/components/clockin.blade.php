@php
    $timedin = 0;
    $timedout = 0;
    $attendance = null;

@endphp
<div class="col-xxl-6 col-xl-12 col-lg-12 float-right">
    <div class="card__wrapper">
        <div class="card__title-wrap mb-20">
            <h5 class="card__heading-title">Attendance Report</h5>
        </div>
        <div class="table__wrapper meeting-table table-responsive">
            <ul class="attendance-list">
                <li>
                    <span>Hi, {{ auth()->user()->name }}</span>
                    <form action="{{ route('attendance.timeIn') }}" method="POST">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-sm btn-success">Check In</button>
                    </form>
                </li>
                <li>
                    <span>Leaves:</span>
                    <span>0</span>
                </li>
                <li>
                    <span>Discrepencry:</span>
                    <span>0</span>
                </li>
            </ul>

        </div>
    </div>
</div>  