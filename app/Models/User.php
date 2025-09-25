<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Plank\Metable\Metable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Shift;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions, Metable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'created_by',
        'image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getRole()
    {
        return $this->getRoleNames()->first();
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'assigned_vehicles', 'user_id', 'vehicle_id');
    }
    
    public function assignedVehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'assigned_vehicles', 'assigned_by', 'vehicle_id');
    }

    public function assignedSales()
    {
        return $this->belongsToMany(User::class,'assigned_agents','sales_manager_id');
    }

    public function assignedCustomer()
    {
        return $this->belongsToMany(User::class, 'assigned_agents', 'customer_id', 'agent_id');
    }

    public function assignedAgent()
    {
        return $this->belongsToMany(User::class, 'assigned_agents', 'agent_id', 'customer_id')->limit(1);
    }

    public function getAssignedAgentAttribute()
    {
        return $this->belongsToMany(User::class, 'assigned_agents', 'customer_id', 'agent_id')->first();
    }

    public function shiftTiming()
    {
        return Shift::find($this->getMeta('shift_id'));
    }

    public function profileImage()
    {
        return $this->image ? asset($this->image) : asset('img/user.png');
    }

    /**
     * Get the current shift window considering night shifts
     */
    public function getCurrentShiftWindow()
    {
        $shift = $this->shiftTiming();
        if (!$shift) {
            return null;
        }

        $now = now();
        $today = $now->copy()->startOfDay();
        
        // Parse shift times for today (shift times are stored as time fields)
        $shiftStart = $today->copy()->setTimeFrom($shift->start_time);
        $shiftEnd = $today->copy()->setTimeFrom($shift->end_time);
        
        // If shift crosses midnight (end time is before start time)
        if ($shiftEnd->lessThan($shiftStart)) {
            // For night shifts, the shift actually ends tomorrow
            $shiftEnd->addDay();
            
            // If current time is before shift start but after midnight
            // and shift end is tomorrow, then we're in yesterday's shift
            if ($now->lessThan($shiftStart) && $now->hour < 12) {
                $shiftStart->subDay();
                $shiftEnd->subDay();
            }
        }
        
        return [
            'start' => $shiftStart,
            'end' => $shiftEnd,
            'shift' => $shift
        ];
    }

    /**
     * Check if user is currently within their shift time
     */
    public function isWithinShiftTime()
    {
        $shiftWindow = $this->getCurrentShiftWindow();
        if (!$shiftWindow) {
            return false;
        }

        $now = now();
        return $now->between($shiftWindow['start'], $shiftWindow['end']);
    }

    public function getDepartment(){
        return Department::find($this->getMeta('department_id'))->name;
    }

    /**
     * Get the appropriate date for attendance (handles night shifts)
     */
    public function getAttendanceDate()
    {
        $shiftWindow = $this->getCurrentShiftWindow();
        if (!$shiftWindow) {
            return now()->startOfDay()->timestamp;
        }

        $now = now();
        
        if ($shiftWindow['end']->isTomorrow() && $now->hour < 12) {
            return $now->subDay()->startOfDay()->timestamp;
        }
        
        return $now->startOfDay()->timestamp;
    }

    /**
     * Get formatted shift time display
     */
    public function getFormattedShiftTime()
    {
        $shift = $this->shiftTiming();
        if (!$shift) {
            return 'No shift assigned';
        }

        $startTime = $shift->start_time->format('h:i A');
        $endTime = $shift->end_time->format('h:i A');
        
        // Check if it's a night shift
        if ($shift->end_time->lessThan($shift->start_time)) {
            return "{$startTime} - {$endTime} (Next Day)";
        }
        
        return "{$startTime} - {$endTime}";
    }

    /**
     * Get the current active attendance for the user
     */
    public function getCurrentAttendance()
    {
        // 1) If there is any open attendance (time_out is null), return it
        //    but ONLY if we are not past "shift end + 3 hours" for that attendance date
        $openAttendance = Attendance::where('user_id', $this->id)
            ->whereNull('time_out')
            ->latest('time_in')
            ->first();

        if ($openAttendance) {
            $shift = $this->shiftTiming();
            if ($shift) {
                $attendanceDate = \Carbon\Carbon::createFromTimestamp($openAttendance->date)->startOfDay();
                // Build shift window for the recorded attendance date
                $shiftStart = $attendanceDate->copy()->setTimeFrom($shift->start_time);
                $shiftEnd = $attendanceDate->copy()->setTimeFrom($shift->end_time);
                if ($shiftEnd->lessThan($shiftStart)) {
                    $shiftEnd->addDay(); // night shift crosses midnight
                }

                $graceCutoff = $shiftEnd->copy()->addHours(3);
                if (now()->lessThanOrEqualTo($graceCutoff)) {
                    return $openAttendance; // Still within grace window → allow Check Out
                }
                // Past grace window → treat as "forgot to timeout"; do not return as active
            } else {
                // No shift assigned: apply a conservative grace period based on calendar day
                $attendanceDate = \Carbon\Carbon::createFromTimestamp($openAttendance->date)->startOfDay();
                $graceCutoff = $attendanceDate->copy()->addDay()->addHours(3); // end of day + 3h
                if (now()->lessThanOrEqualTo($graceCutoff)) {
                    return $openAttendance;
                }
                // Else, beyond grace → treat as forgotten
            }
        }

        $shiftWindow = $this->getCurrentShiftWindow();
        if ($shiftWindow) {
            $date = $this->getAttendanceDate();
        } else {
            // Fallback logic when no shift is assigned: early morning belongs to previous day
            $now = now();
            $date = ($now->hour >= 0 && $now->hour <= 6)
                ? $now->copy()->subDay()->startOfDay()->timestamp
                : $now->copy()->startOfDay()->timestamp;
        }

        return Attendance::where('user_id', $this->id)
            ->where('date', $date)
            ->latest('time_in')
            ->first();
    }

    public function customerForum()
    {
        return $this->hasMany(Forum::class,'customer_id');
    }

    public function agentForum()
    {
        return $this->hasMany(Forum::class,'agent_id');
    }

    public function invoice()
    {
        return $this->hasMany(Invoice::class, 'customer_id');
    }

    public function agentInvoice()
    {
        return $this->hasMany(Invoice::class, 'agent_id');
    }


    
}
