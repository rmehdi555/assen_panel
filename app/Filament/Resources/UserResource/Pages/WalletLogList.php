<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Credit;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class WalletLogList extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.user-resource.wallet-log-list';

    public User $user;

    public function getTitle(): string
    {
        return 'لاگ کیف پول کاربر: ' . $this->user->name;
    }

    public function mount($record): void
    {
        $this->user = User::find($record);
    }

    protected function getTableQuery(): Builder
    {
        return Credit::query()->where('user_id', $this->user->id)->orderByDesc('id');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('amount')->label('مقدار (ریال)'),
            TextColumn::make('payment_status')->label('وضعیت پرداخت'),
            TextColumn::make('description')->label('توضیحات'),
            TextColumn::make('status')->label('نوع')->formatStateUsing(function (string $state): string {
                if ($state == 'charge') return 'شارژ';
                if ($state == 'discharge') return 'کسر شارژ';
                if ($state == 'admin-charge') return 'شارژ توسط ادمین';
                if ($state == 'admin-discharge') return 'کسر شارژ توسط ادمین';
            }),
            TextColumn::make('invoice.code')->label('کد سفارش')->copyable(),
            TextColumn::make('created_at')->label('تاریخ'),
        ];
    }
}
