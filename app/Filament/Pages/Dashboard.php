<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardArticleChart;
use App\Filament\Widgets\DashboardInvoiceChart;
use App\Filament\Widgets\DashboardPaymentChart;
use App\Filament\Widgets\DashboardStates;
use App\Filament\Widgets\DashboardUserChart;
use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    public function getWidgets(): array
    {
        return [
            DashboardStates::class,
//            DashboardInvoiceChart::class,
            DashboardUserChart::class,
            DashboardArticleChart::class,
//            DashboardPaymentChart::class,
        ];
    }
}
