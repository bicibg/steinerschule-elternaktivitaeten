<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'bulletin_post_id',
        'role',
        'time',
        'needed',
        'filled',
    ];

    public function bulletinPost()
    {
        return $this->belongsTo(BulletinPost::class);
    }

    public function volunteers()
    {
        return $this->hasMany(ShiftVolunteer::class);
    }
}
