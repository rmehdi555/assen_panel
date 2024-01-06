<?php

namespace App\Filament\Widgets;

use App\Models\Exchanges;
use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class DashboardStates extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('تعداد استعلام های امروز', $this->invoiceToday())
                ->icon('heroicon-o-check')
                ->color('success'),
            Card::make('مجموع تراکنش های امروز (ریال)', number_format($this->transactionSumToday()))
                ->icon('heroicon-o-arrow-trending-up')
                ->color('success'),
            Card::make('قیمت امروز دلار (ریال)', number_format($this->showDollarPrice()))
                ->icon('heroicon-o-clipboard-document-list')
                ->color('success'),
            Card::make('تعداد تیکت های بسته نشده', $this->tickets())
                ->icon('heroicon-o-exclamation-triangle')
                ->color('success')
        ];
    }

    private function tickets(): int
    {
        return Ticket::whereNot('status_id', 7)->count();
    }

    private function invoiceToday(): int
    {
        return Invoice::where('invoicedate', Carbon::today())->count();
    }

    private function showDollarPrice(): int
    {
        return Exchanges::find(1)->value;
    }

    private function transactionSumToday(): int
    {
        return Transaction::whereDate('created_at', Carbon::today())->where('issuccess', 1)->sum('amount');
    }
}
