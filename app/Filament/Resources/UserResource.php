<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\UsersAddressesRelationManager;
use App\Models\Credit;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $modelLabel = 'کاربر';

    protected static ?string $pluralModelLabel = 'لیست کاربران';

    protected static ?string $slug = 'users';

    protected static ?string $navigationGroup = 'مدیریت کاربران';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(1)->schema([
                    TextInput::make('name')->label('نام')->required(),
                    TextInput::make('email')->label('ایمیل')->required()->email(),
                    TextInput::make('cell_number')->label('شماره موبایل')->required()->numeric()->minLength(11),
                    TextInput::make('nationalcode')->label('کد ملی')->numeric()->required()->length(10),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('آی دی'),
                TextColumn::make('name')->label('نام'),
                TextColumn::make('email')->label('ایمیل'),
                TextColumn::make('created_at')->label('تاریخ ثبت نام'),
                TextColumn::make('cell_number')->label('شماره موبایل'),
                TextColumn::make('wallet_balance')->label('شارژ کیف پول')->money('irr'),
            ])
            ->filters([
                Filter::make('name')->form([
                    TextInput::make('name')->label('نام'),
                ])->query(fn(Builder $query, array $data): Builder => $query->when(
                    $data['name'],
                    fn(Builder $query, $data): Builder => $query->where('users.name', 'like', '%' . $data . '%'),
                )),

                Filter::make('cell_number')->form([
                    TextInput::make('cell_number')->label('شماره موبایل'),
                ])->query(fn(Builder $query, array $data): Builder => $query->when(
                    $data['cell_number'],
                    fn(Builder $query, $data): Builder => $query->where('users.cell_number', 'like', $data . '%'),
                )),

                Filter::make('createdate_from')->form([
                    DatePicker::make('cdate_from')->label('ثبت نام از تاریخ')->displayFormat('Y-m-d'),
                ])->query(fn(Builder $query, array $data): Builder => $query->when(
                    $data['cdate_from'],
                    fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                )),

                Filter::make('createdate_until')->form([
                    DatePicker::make('cdate_until')->label('ثبت نام تا تاریخ')->displayFormat('Y-m-d'),
                ])->query(fn(Builder $query, array $data): Builder => $query->when(
                    $data['cdate_until'],
                    fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                )),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Action::make('recharge-wallet')
                    ->label('شارژ کیف پول')
                    ->color('danger')
                    ->icon('heroicon-o-currency-dollar')
                    ->form([
                        TextInput::make('amount')->label('مقدار (ریال)')->required()->minValue(0)->mask(RawJs::make('$money($input)'))
                            ->dehydrateStateUsing(fn(string $state): string => preg_replace("/[^0-9]/", "", $state)),
                        TextInput::make('bank_reference_id')->label('شماره تراکنش')->required(),
                        Select::make('status')->label('نوع تراکنش')->required()->options([
                            'admin-charge' => 'افزودن به کیف پول',
                            'admin-discharge' => 'کسر از کیف پول',
                        ]),
                        Select::make('invoice_id')->label('کد سفارش')->model(Credit::class)
                            ->searchable()
                            ->relationship('invoice', 'code')
                            ->hint('در صورت پر کردن این فیلد این تراکنش وارد بخش مالی می شود!'),
                        Textarea::make('description')->label('توضیحات'),
                    ])
                    ->action(function (User $record, array $data) {
                        if ($data['status'] == 'admin-discharge' and Credit::where('user_id', $record->id)->where('payment_status', 'Succeeded')->sum('amount') < $data['amount']) {
                            Notification::make()->title('مبلغ وارد شده از شارژ کیف پول بیشتر میباشد')->danger()->send();
                            return $record;
                        }
                        if (isset(Invoice::find($data['invoice_id'])->user_id) and Invoice::find($data['invoice_id'])->user_id != $record->id) {
                            Notification::make()->title('این سفارش برای این کاربر نیست.')->danger()->send();
                            return $record;
                        }

                        Credit::create([
                            'amount' => ($data['status'] == 'admin-charge') ? $data['amount'] : $data['amount'] * -1,
                            'bank_reference_id' => $data['bank_reference_id'],
                            'status' => $data['status'],
                            'description' => $data['description'],
                            'user_id' => $record->id,
                            'payment_status' => 'Succeeded',
                            'invoice_id' => $data['invoice_id'] ?? null,
                            'admin_user_id' => Auth::id(),
                        ]);

                        if (filled($data['invoice_id']))
                            Transaction::create([
                                'method' => ($data['status'] == 'admin-charge') ? 'عودت وجه' : 'پرداخت از کیف پول',
                                'amount' => ($data['status'] == 'admin-charge') ? $data['amount'] * -1 : $data['amount'],
                                'issuccess' => true,
                                'date' => now(),
                                'payment_method_id' => 6,
                                'comment' => $data['description'] . PHP_EOL . '(تراکنش مربوط به کیف پول)',
                                'user_id' => $record->id,
                                'invoice_id' => $data['invoice_id'],
                                'admin_user_id' => Auth::id(),
                            ]);
                        Notification::make()->title('موفقیت آمیز بود')->success()->send();
                        $record->update([
                            'wallet_balance' => Credit::where('user_id', $record->id)->where('payment_status', 'Succeeded')->sum('amount')
                        ]);

                        return $record;

                    }),

                Action::make('wallet-log-list')
                    ->label('لاگ کیف پول')
                    ->color('warning')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->url(fn($record): string => self::getUrl('wallet-log-list', [$record->id])),

            ])
            ->bulkActions([])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            UsersAddressesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'wallet-log-list' => Pages\WalletLogList::route('/wallet-log/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('users::view');
    }
}
