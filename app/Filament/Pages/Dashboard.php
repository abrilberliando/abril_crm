<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ManagerStatsOverview;
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
            ];
        }
        // Dashboard kosong untuk role lain
        return [];
    }
}
