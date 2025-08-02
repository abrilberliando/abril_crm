<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ManagerStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Calon Customer', Lead::count())
                ->description('Semua prospek yang masuk')
                ->color('primary'),
            Stat::make('Total Project Aktif', Project::where('status', 'pending')->count())
                ->description('Project yang sedang diproses')
                ->color('warning'),
            Stat::make('Total Pelanggan', Customer::count())
                ->description('Pelanggan yang sudah berlangganan')
                ->color('success'),
        ];
    }
}
