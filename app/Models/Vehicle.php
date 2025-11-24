<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'condition',
        'steering_type',
        'chassis_engine_no',
        'make',
        'model',
        'body_type',
        'stock_id',
        'year',
        'offer_type',
        'drive_type',
        'transmission',
        'fuel_type',
        'mileage',
        'color',
        'doors',
        'features',
        'safety_features',
        'availability',
        'price',
        'video',
        'created_by',
    ];

    protected $casts = [
        'year' => 'integer',
        'mileage' => 'integer',
        'doors' => 'integer',
        'price' => 'decimal:2',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function photos()
    {
        return $this->hasMany(VehiclePhoto::class)->orderBy('order');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('availability', 'Available');
    }

    public function scopeReserved($query)
    {
        return $query->where('availability', 'Reserved');
    }

    public function scopeSold($query)
    {
        return $query->where('availability', 'Sold Out');
    }
}
