<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\Exchanges;
use App\Models\Invoice;
use App\Models\Products;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use PhpOffice\PhpSpreadsheet\Calculation\Database\DProduct;

class DashboardStates extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Stat::make('تعداد مقالات', number_format($this->articlesCount()))
                ->icon('heroicon-o-check')
                ->color('success'),
            Stat::make('تعداد کاربران', number_format($this->usersCount()))
                ->icon('heroicon-o-arrow-trending-up')
                ->color('success'),
            Stat::make('تعداد محصولات سایت', number_format($this->productsCount()))
                ->icon('heroicon-o-arrow-trending-up')
                ->color('success'),
//            Stat::make('قیمت امروز دلار (ریال)', number_format($this->showDollarPrice()))
//                ->icon('heroicon-o-clipboard-document-list')
//                ->color('success'),
//            Stat::make('تعداد تیکت های بسته نشده', $this->tickets())
//                ->icon('heroicon-o-exclamation-triangle')
//                ->color('success')
        ];
    }

    private function articlesCount(): int
    {
        return Article::where('is_show', 1)->count();
    }

    private function usersCount(): int
    {
        return User::all()->count();
    }
    private function productsCount(): int
    {
        return Products::where('is_show', 1)->count();
    }

//    private function showDollarPrice(): int
//    {
//        return Exchanges::find(1)->value;
//    }
//
//    private function transactionSumToday(): int
//    {
//        return Transaction::whereDate('created_at', Carbon::today())->where('issuccess', 1)->sum('amount');
//    }
}
