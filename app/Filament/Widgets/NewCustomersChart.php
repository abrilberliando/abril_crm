<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class NewCustomersChart extends ChartWidget
{
    protected static ?string $heading = 'Pertumbuhan Pelanggan Baru';
    protected static string $color = 'success';

    protected function getData(): array
    {
        $data = Customer::select(DB::raw('COUNT(id) as count, EXTRACT(MONTH FROM created_at) as month'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->all();

        return [
            'datasets' => [
                [
                    'label' => 'Pelanggan Baru',
                    'data' => array_values($data),
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
