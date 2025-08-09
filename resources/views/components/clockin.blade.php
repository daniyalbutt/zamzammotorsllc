@php
    use Carbon\Carbon;

    // Get user's current shift window
$shiftWindow = Auth::user()->getCurrentShiftWindow();

if ($shiftWindow) {
    // Get the appropriate attendance date (handles night shifts)
    $attendanceDate = Auth::user()->getAttendanceDate();

    // Get latest attendance for the appropriate date
    $attendance = App\Models\Attendance::where('user_id', Auth::id())
        ->where('date', $attendanceDate)
        ->latest()
        ->first();
} else {
    // If no shift assigned, just check today's attendance
    $attendance = Auth::user()->getCurrentAttendance();
}
@endphp

<div class="col-xxl-6 col-xl-12 col-lg-12 float-right">
    <div class="card__wrapper">
        <div class="card__title-wrap mb-20">
            <h5 class="card__heading-title">Attendance Report</h5>
        </div>
        <div class="table__wrapper meeting-table table-responsive">
            <ul class="attendance-list">
                <li class="att-li">
                    <div>
                        <span>Hi, {{ auth()->user()->name }}</span>
                        <br>
                        <span>{{ Auth::user()->getMeta('designation') }}</span>
                        @if ($shiftWindow)
                            <br>
                            <small class="text-muted">
                                Shift: {{ $shiftWindow['shift']->name }}
                                ({{ Auth::user()->getFormattedShiftTime() }})
                            </small>
                            <br>
                            <small class="text-muted">
                                Current Status:
                                @if (Auth::user()->isWithinShiftTime())
                                    <span class="text-success">Within Shift Time</span>
                                @else
                                    <span class="text-warning">Outside Shift Time</span>
                                @endif
                            </small>
                        @endif
                    </div>
                    {{-- Attendance button logic --}}
                    @if (!$attendance)

                        {{-- Not checked in yet --}}
                        <form action="{{ route('attendance.timeIn') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">
                                Check In
                            </button>
                        </form>
                        
                        @if($shiftWindow)
                            <small class="text-muted">
                                @if ($shiftWindow['end']->isTomorrow())
                                    Night shift ends at {{ $shiftWindow['shift']->end_time->format('h:i A') }} tomorrow
                                @else
                                    Shift time: {{ Auth::user()->getFormattedShiftTime() }}
                                @endif
                            </small>
                            <br>
                            <small class="text-muted">
                                @if (Auth::user()->isWithinShiftTime())
                                    <span class="text-success">✓ Within Shift Time</span>
                                @else
                                    <span class="text-warning">⚠ Outside Shift Time</span>
                                @endif
                            </small>
                        @else
                            <small class="text-muted">No shift assigned</small>
                        @endif
                    @elseif (is_null($attendance->time_out))

                        {{-- Checked in but not out yet --}}
                        <div>
                            <small>Checked in at:
                                {{ $attendance->formatted_time_in }}
                            </small>
                            <br>
                            <small>Date: {{ $attendance->formatted_date }}</small>
                            <br>
                            <small class="text-dark">
                                <strong>Current Time: <span id="current-time"
                                        class="live-timer">--:--:--</span></strong>
                            </small>
                            <br>
                            <small class="text-primary">
                                <strong>Elapsed Time: <span id="elapsed-timer" class="live-timer"
                                        data-time-in="{{ $attendance->time_in }}">--:--:--</span></strong>
                            </small>
                            <br>
                            <small class="text-success">
                                <strong>Current Total: <span id="current-total-timer" class="live-timer"
                                        data-time-in="{{ $attendance->time_in }}">--:--:--</span></strong>
                            </small>
                            @if ($shiftWindow)
                                <br>
                                <small class="text-info">
                                    <span id="shift-progress" data-shift-start="{{ $shiftWindow['start']->timestamp }}"
                                        data-shift-end="{{ $shiftWindow['end']->timestamp }}">
                                        Shift Progress: --%
                                    </span>
                                </small>
                                <br>
                                <small class="text-secondary">
                                    <span id="remaining-time" data-shift-end="{{ $shiftWindow['end']->timestamp }}">
                                        Remaining: --:--:--
                                    </span>
                                </small>
                            @endif
                        </div>

                        <style>
                            .live-timer {
                                animation: pulse 2s infinite;
                                font-weight: bold;
                            }

                            @keyframes pulse {
                                0% {
                                    opacity: 1;
                                }

                                50% {
                                    opacity: 0.7;
                                }

                                100% {
                                    opacity: 1;
                                }
                            }
                        </style>
                        <form action="{{ route('attendance.timeOut') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">
                                Check Out
                            </button>
                        </form>

                        <script>
                            // Live timer for elapsed time and shift progress
                            function updateTimers() {
                                // Update current time
                                const currentTimeElement = document.getElementById('current-time');
                                if (currentTimeElement) {
                                    const now = new Date();
                                    const timeString = now.toLocaleTimeString('en-US', {
                                        hour12: true,
                                        hour: '2-digit',
                                        minute: '2-digit',
                                        second: '2-digit'
                                    });
                                    currentTimeElement.textContent = timeString;
                                }

                                // Update elapsed timer
                                const timeInElement = document.getElementById('elapsed-timer');
                                if (timeInElement) {
                                    const timeIn = parseInt(timeInElement.getAttribute('data-time-in'));
                                    const now = Math.floor(Date.now() / 1000);
                                    const elapsed = now - timeIn;

                                    if (elapsed > 0) {
                                        const hours = Math.floor(elapsed / 3600);
                                        const minutes = Math.floor((elapsed % 3600) / 60);
                                        const seconds = elapsed % 60;

                                        let timeString = '';
                                        if (hours > 0) {
                                            timeString = `${hours}h ${minutes}m ${seconds}s`;
                                        } else if (minutes > 0) {
                                            timeString = `${minutes}m ${seconds}s`;
                                        } else {
                                            timeString = `${seconds}s`;
                                        }

                                        timeInElement.textContent = timeString;
                                    }
                                }

                                // Update current total timer
                                const currentTotalTimer = document.getElementById('current-total-timer');
                                if (currentTotalTimer) {
                                    const timeIn = parseInt(currentTotalTimer.getAttribute('data-time-in'));
                                    const now = Math.floor(Date.now() / 1000);
                                    const elapsed = now - timeIn;

                                    if (elapsed > 0) {
                                        const hours = Math.floor(elapsed / 3600);
                                        const minutes = Math.floor((elapsed % 3600) / 60);
                                        const seconds = elapsed % 60;

                                        let timeString = '';
                                        if (hours > 0) {
                                            timeString = `${hours}h ${minutes}m ${seconds}s`;
                                        } else if (minutes > 0) {
                                            timeString = `${minutes}m ${seconds}s`;
                                        } else {
                                            timeString = `${seconds}s`;
                                        }

                                        currentTotalTimer.textContent = timeString;
                                    }
                                }

                                // Update shift progress
                                const shiftProgressElement = document.getElementById('shift-progress');
                                if (shiftProgressElement) {
                                    const shiftStart = parseInt(shiftProgressElement.getAttribute('data-shift-start'));
                                    const shiftEnd = parseInt(shiftProgressElement.getAttribute('data-shift-end'));
                                    const now = Math.floor(Date.now() / 1000);

                                    if (shiftStart && shiftEnd) {
                                        const totalShiftDuration = shiftEnd - shiftStart;
                                        const elapsedInShift = now - shiftStart;

                                        if (totalShiftDuration > 0) {
                                            let progress = (elapsedInShift / totalShiftDuration) * 100;
                                            progress = Math.max(0, Math.min(100, progress)); // Clamp between 0-100

                                            const progressText = `Shift Progress: ${progress.toFixed(1)}%`;
                                            shiftProgressElement.textContent = progressText;

                                            // Change color based on progress
                                            if (progress >= 100) {
                                                shiftProgressElement.className = 'text-danger';
                                            } else if (progress >= 75) {
                                                shiftProgressElement.className = 'text-warning';
                                            } else {
                                                shiftProgressElement.className = 'text-info';
                                            }
                                        }
                                    }
                                }

                                // Update remaining time
                                const remainingTimeElement = document.getElementById('remaining-time');
                                if (remainingTimeElement) {
                                    const shiftEnd = parseInt(remainingTimeElement.getAttribute('data-shift-end'));
                                    const now = Math.floor(Date.now() / 1000);

                                    if (shiftEnd) {
                                        const remaining = shiftEnd - now;

                                        if (remaining > 0) {
                                            const hours = Math.floor(remaining / 3600);
                                            const minutes = Math.floor((remaining % 3600) / 60);
                                            const seconds = remaining % 60;

                                            let timeString = '';
                                            if (hours > 0) {
                                                timeString = `${hours}h ${minutes}m ${seconds}s`;
                                            } else if (minutes > 0) {
                                                timeString = `${minutes}m ${seconds}s`;
                                            } else {
                                                timeString = `${seconds}s`;
                                            }

                                            remainingTimeElement.textContent = `Remaining: ${timeString}`;

                                            // Change color based on remaining time
                                            if (remaining <= 3600) { // Less than 1 hour
                                                remainingTimeElement.className = 'text-danger';
                                            } else if (remaining <= 7200) { // Less than 2 hours
                                                remainingTimeElement.className = 'text-warning';
                                            } else {
                                                remainingTimeElement.className = 'text-secondary';
                                            }
                                        } else {
                                            remainingTimeElement.textContent = 'Shift Ended';
                                            remainingTimeElement.className = 'text-danger';
                                        }
                                    }
                                }
                            }

                            // Update timers every second
                            setInterval(updateTimers, 1000);
                            updateTimers(); // Initial update
                        </script>
                    @else

                    
                        {{-- Already checked out --}}
                        <div>
                            <small>Checked in at:
                                {{ $attendance->formatted_time_in }}
                            </small>
                            <br>
                            <small>Checked out at:
                                {{ $attendance->formatted_time_out }}
                            </small>
                            <br>
                            <small>Date: {{ $attendance->formatted_date }}</small>
                            <br>
                            <small class="text-success">
                                <strong>Total Time: {{ $attendance->formatted_total_hours }}</strong>
                            </small>
                        </div>
                    @endif
                </li>

                <li>
                    <span>Leaves:</span>
                    <span>0</span>
                </li>
                <li>
                    <span>Discrepancy:</span>
                    <span>0</span>
                </li>
            </ul>
        </div>
    </div>
</div>
