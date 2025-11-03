<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Models extends Model
{
    protected $guarded = [];
    use HasFactory, HasSlug;

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
        ->generateSlugsFrom('name')
        ->saveSlugsTo('slug');
    }
}
