<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'features' => 'array',
        'safety_features' => 'array',
        'image_paths' => 'array',
        'status' => 'boolean',
        'year' => 'integer',
        'cylinders' => 'integer',
        'make_id' => 'integer',
        'model_id' => 'integer',
        'body_type_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function bodyType()
    {
        return $this->belongsTo(BodyType::class);
    }

    public function model()
    {
        return $this->belongsTo(Models::class, 'model_id');
    }

    public function make()
    {
        return $this->belongsTo(Make::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all image URLs
     */
    public function getImageUrlsAttribute()
    {
        if (!$this->image_paths) {
            return [];
        }

        return array_map(function ($path) {
            return asset('storage/' . $path);
        }, $this->image_paths);
    }

    /**
     * Get the first image URL
     */
    public function getFirstImageUrlAttribute()
    {
        $urls = $this->getImageUrlsAttribute();
        return count($urls) > 0 ? $urls[0] : null;
    }

    /**
     * Get features as array (for backward compatibility)
     */
    public function getFeaturesArrayAttribute()
    {
        return $this->features ?? [];
    }

    /**
     * Get safety features as array (for backward compatibility)
     */
    public function getSafetyFeaturesArrayAttribute()
    {
        return $this->safety_features ?? [];
    }

    /**
     * Check if vehicle has images
     */
    public function hasImages()
    {
        return $this->image_paths && count($this->image_paths) > 0;
    }

    /**
     * Get image count
     */
    public function getImageCountAttribute()
    {
        return $this->image_paths ? count($this->image_paths) : 0;
    }

    public function assigned_users()
    {
        return $this->belongsToMany(User::class, 'assigned_vehicles', 'vehicle_id', 'user_id');
    }

    public function assigned()
    {
        if ($this->assigned_users->isNotEmpty()) {
            $name = $this->assigned_users->first()->name;
            return '<span class="badge bg-success" data-toggle="tooltip" data-placement="bottom" title="' . $name . '">Assigned</span>';
        } else {
            return '<span class="badge bg-danger">Not Assigned</span>';
        }
    }
}
