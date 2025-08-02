<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();

        return [
            Stat::make('Prospek Baru Saya', Lead::where('sales_id', $userId)->where('status', 'new')->count())
                ->color('primary'),
            Stat::make('Project Aktif Saya', Project::where('sales_id', $userId)->where('status', 'pending')->count())
                ->color('warning'),
        ];
    }
}
