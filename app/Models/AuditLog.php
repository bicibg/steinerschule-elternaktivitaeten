<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'action_type',
        'action_name',
        'performed_by',
        'performed_by_name',
        'ip_address',
        'metadata',
        'description',
        'severity',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    // Helper method to create audit log
    public static function log(string $actionType, string $actionName, array $metadata = [], string $description = null, string $severity = 'info')
    {
        return static::create([
            'action_type' => $actionType,
            'action_name' => $actionName,
            'performed_by' => auth()->id(),
            'performed_by_name' => auth()->user()->name,
            'ip_address' => request()->ip(),
            'metadata' => $metadata,
            'description' => $description,
            'severity' => $severity,
        ]);
    }

    // Get last action of a specific type
    public static function getLastAction(string $actionType)
    {
        return static::where('action_type', $actionType)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    // Check if action was performed recently
    public static function wasActionPerformedRecently(string $actionType, int $days = 30)
    {
        $lastAction = static::getLastAction($actionType);

        if (!$lastAction) {
            return false;
        }

        return $lastAction->created_at->diffInDays(now()) < $days;
    }

    // Scope for critical actions
    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    // Scope for actions by type
    public function scopeOfType($query, string $type)
    {
        return $query->where('action_type', $type);
    }
}