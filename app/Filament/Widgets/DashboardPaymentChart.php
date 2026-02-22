<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DashboardPaymentChart extends LineChartWidget
{
    protected ?string $heading = 'حجم ریالی فروش به تفکیک روز';

    protected function getData(): array
    {
        $data = Trend::query(
            Transaction::query()->where('issuccess', 1)
        )
            ->between(
                start: now()->subDays(30),
                end: now(),
            )
            ->perDay()
            ->sum('amount');
        return [
            'datasets' => [
                [
                    'label' => 'جمع فروش',
                    'borderColor' => "#ea580c",
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }
}
