<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ManagerStatsOverview;
use App\Filament\Widgets\MyRecentLeadsTable;
use App\Filament\Widgets\NewCustomersChart;
use App\Filament\Widgets\SalesStatsOverview;
use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    public function getWidgets(): array
    {
        $user = auth()->user();

        // Jika user memiliki role 'manager'
        if ($user->hasRole('manager')) {
            return [
                ManagerStatsOverview::class,
                NewCustomersChart::class,
            ];
        }

        // Jika user memiliki role 'sales'
        if ($user->hasRole('sales')) {
            return [
                SalesStatsOverview::class,
                MyRecentLeadsTable::class,
            ];
        }

        // Dashboard kosong untuk role lain
        return [];
    }
}
