<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    /**
     * Get the user that owns the attendance.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the elapsed time since check-in in seconds
     */
    public function getElapsedTimeAttribute()
    {
        if (!$this->time_in) {
            return 0;
        }
        
        $timeOut = $this->time_out ?? time();
        return $timeOut - $this->time_in;
    }

    /**
     * Get the elapsed time formatted as human readable string
     */
    public function getFormattedElapsedTimeAttribute()
    {
        $elapsed = $this->elapsed_time;
        
        if ($elapsed <= 0) {
            return '0s';
        }
        
        $hours = floor($elapsed / 3600);
        $minutes = floor(($elapsed % 3600) / 60);
        $seconds = $elapsed % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}m {$seconds}s";
        } elseif ($minutes > 0) {
            return "{$minutes}m {$seconds}s";
        } else {
            return "{$seconds}s";
        }
    }

    /**
     * Check if attendance is currently active (checked in but not out)
     */
    public function getIsActiveAttribute()
    {
        return !is_null($this->time_in) && is_null($this->time_out);
    }

    /**
     * Get the formatted date from timestamp
     */
    public function getFormattedDateAttribute()
    {
        if (!$this->date) {
            return 'N/A';
        }
        
        return date('M d, Y', $this->date);
    }

    /**
     * Get the formatted time in from timestamp
     */
    public function getFormattedTimeInAttribute()
    {
        if (!$this->time_in) {
            return 'N/A';
        }
        
        return date('h:i A', $this->time_in);
    }

    /**
     * Get the formatted time out from timestamp
     */
    public function getFormattedTimeOutAttribute()
    {
        if (!$this->time_out) {
            return 'N/A';
        }
        
        return date('h:i A', $this->time_out);
    }

    /**
     * Get the day name from the date timestamp
     */
    public function getDayNameAttribute()
    {
        if (!$this->date) {
            return 'N/A';
        }
        
        return date('l', $this->date);
    }

    /**
     * Get the total hours in decimal format (for calculations)
     */
    public function getTotalHoursDecimalAttribute()
    {
        if (!$this->totalhours) {
            return 0;
        }
        
        return round($this->totalhours / 3600, 2);
    }

    /**
     * Get the total hours formatted as human readable string
     */
    public function getFormattedTotalHoursAttribute()
    {
        if (!$this->totalhours) {
            return '0h 0m 0s';
        }
        
        $totalSeconds = $this->totalhours;
        
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}m {$seconds}s";
        } elseif ($minutes > 0) {
            return "{$minutes}m {$seconds}s";
        } else {
            return "{$seconds}s";
        }
    }

    /**
     * Get the total hours in hours only (for display)
     */
    public function getTotalHoursOnlyAttribute()
    {
        if (!$this->totalhours) {
            return 0;
        }
        
        return floor($this->totalhours / 3600);
    }

    /**
     * Get the total minutes only (for display)
     */
    public function getTotalMinutesOnlyAttribute()
    {
        if (!$this->totalhours) {
            return 0;
        }
        
        return floor(($this->totalhours % 3600) / 60);
    }

    /**
     * Get current total hours for active attendance (real-time calculation)
     */
    public function getCurrentTotalHoursAttribute()
    {
        if (!$this->time_in) {
            return 0;
        }
        
        $timeOut = $this->time_out ?? time();
        return $timeOut - $this->time_in;
    }

    /**
     * Get current total hours formatted for active attendance
     */
    public function getCurrentFormattedTotalHoursAttribute()
    {
        $totalSeconds = $this->current_total_hours;
        
        if ($totalSeconds <= 0) {
            return '0s';
        }
        
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}m {$seconds}s";
        } elseif ($minutes > 0) {
            return "{$minutes}m {$seconds}s";
        } else {
            return "{$seconds}s";
        }
    }
}
