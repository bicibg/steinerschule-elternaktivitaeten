<?php

namespace App\Filament\Resources\AuditLogResource\Widgets;

use App\Models\AuditLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AuditLogStats extends BaseWidget
{
    protected function getStats(): array
    {
        $lastReset = AuditLog::where('action_type', 'year_reset')
            ->orderBy('created_at', 'desc')
            ->first();

        $criticalCount = AuditLog::where('severity', 'critical')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        $todayCount = AuditLog::whereDate('created_at', today())->count();

        return [
            Stat::make('Heutige Aktionen', $todayCount)
                ->description('Protokolleinträge heute')
                ->icon('heroicon-o-calendar')
                ->color($todayCount > 10 ? 'warning' : 'success'),

            Stat::make('Kritische Aktionen', $criticalCount)
                ->description('Letzte 30 Tage')
                ->icon('heroicon-o-exclamation-triangle')
                ->color($criticalCount > 0 ? 'danger' : 'success'),

            Stat::make('Letztes Schuljahr-Reset', $lastReset ? $lastReset->created_at->diffForHumans() : 'Noch nie')
                ->description($lastReset ? $lastReset->created_at->format('d.m.Y H:i') : 'Kein Reset durchgeführt')
                ->icon('heroicon-o-academic-cap')
                ->color('info'),
        ];
    }
}