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
        'created_by'
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

    public function assignedCustomer()
    {
        return $this->belongsToMany(User::class, 'assigned_agents', 'agent_id', 'customer_id');
    }

    public function assignedAgent()
    {
        return $this->belongsToMany(User::class, 'assigned_agents', 'customer_id', 'agent_id')->limit(1);
    }

    public function getAssignedAgentAttribute()
    {
        return $this->belongsToMany(User::class, 'assigned_agents', 'customer_id', 'agent_id')->first();
    }

    public function shiftTiming()
    {
        return Shift::find($this->getMeta('shift_id'));
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
        $shiftWindow = $this->getCurrentShiftWindow();
        if (!$shiftWindow) {
            $date = strtotime(date('Y-m-d'));
        } else {
            $date = $this->getAttendanceDate();
        }
        
        return Attendance::where('user_id', $this->id)
            ->where('date', $date)
            ->whereNull('time_out')
            ->first();
    }
}
