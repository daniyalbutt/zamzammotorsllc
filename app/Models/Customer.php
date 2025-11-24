<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'consignee_details',
        'lead_source',
        'status',
        'assigned_to',
        'created_by',
    ];

    protected $casts = [
        'consignee_details' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function notes()
    {
        return $this->hasMany(CustomerNote::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // Scopes
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
