<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityPost extends Model
{
    protected $fillable = [
        'activity_id',
        'author_name',
        'body',
        'ip_hash',
        'is_hidden',
    ];

    protected $casts = [
        'is_hidden' => 'boolean',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function comments()
    {
        return $this->hasMany(ActivityComment::class);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false);
    }
}