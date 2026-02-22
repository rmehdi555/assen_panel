<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DashboardInvoiceChart extends LineChartWidget
{
    protected ?string $heading = 'تعداد ثبت سفارش به تفکیک روز';

    protected function getData(): array
    {
        $data = Trend::model(Invoice::class)
            ->between(
                start: now()->subDays(30),
                end: now(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'تعداد سفارش',
                    'borderColor'=> "#ea580c",
                    'data' =>$data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }
}
