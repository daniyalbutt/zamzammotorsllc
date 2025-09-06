<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function payslipsData()
    {
        return $this->hasMany(PayslipData::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'paid' => 'success',
            'unpaid' => 'danger',
            'pending' => 'warning',
            default => 'secondary',
        };
    }
}
