<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumDiscussion extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function media()
    {
        return $this->hasMany(ForumMedia::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
