<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\User;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DashboardArticleChart extends LineChartWidget
{
    protected static ?string $heading = 'تعداد مقالات به تفکیک ماه';

    protected function getData(): array
    {
        $data = Trend::model(Article::class)
            ->between(
                start: now()->subMonth(30),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'تعداد مقالات',
                    'borderColor' => "#ea580c",
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }
}
