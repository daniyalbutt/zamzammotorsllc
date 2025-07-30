<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions;

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
}
