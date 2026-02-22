<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\UsersAddressesRelationManager;
use App\Models\Credit;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-user';

    protected static ?string $modelLabel = 'کاربر';

    protected static ?string $pluralModelLabel = 'لیست کاربران';

    protected static ?string $slug = 'users';

    protected static \UnitEnum|string|null $navigationGroup = 'مدیریت کاربران';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make()->schema([
                TextInput::make('name')->label('نام')->required(),
                TextInput::make('family')->label('نام خانوادگی')->required(),
                TextInput::make('email')->label('ایمیل')->required()->email(),
                TextInput::make('cell_number')->label('شماره موبایل')->required()->numeric()->minLength(11),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('آی دی'),
                TextColumn::make('name')->label('نام'),
                TextColumn::make('family')->label('نام خانوادگی'),
                TextColumn::make('email')->label('ایمیل'),
                TextColumn::make('created_at')->label('تاریخ ثبت نام'),
                TextColumn::make('cell_number')->label('شماره موبایل'),
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
                EditAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('users::view');
    }
}
