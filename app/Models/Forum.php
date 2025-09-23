<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function discussions()
    {
        return $this->hasMany(ForumDiscussion::class);
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function car_price()
    {
        return $this->hasOne(VehiclesPrice::class);
    }


}
