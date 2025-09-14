<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'activity_id',
        'role',
        'time',
        'needed',
        'filled',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function volunteers()
    {
        return $this->hasMany(ShiftVolunteer::class);
    }
}
