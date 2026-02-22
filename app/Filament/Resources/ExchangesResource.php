<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExchangesResource\Pages;
use App\Filament\Resources\ExchangesResource\RelationManagers;
use App\Models\Exchanges;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExchangesResource extends Resource
{
    protected static ?string $model = Exchanges::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $modelLabel = 'قیمت ارز ها';

    protected static ?string $pluralModelLabel = 'قیمت ارز';

    protected static ?int $navigationSort = 5;

    protected static \UnitEnum|string|null $navigationGroup = 'تنظیمات';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')->label('عنوان')->required(),
                TextInput::make('value')->label('قیمت (ریال)')->numeric()->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('عنوان')->searchable(),
                TextColumn::make('value')->label('قیمت (ریال)'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExchanges::route('/'),
            'create' => Pages\CreateExchanges::route('/create'),
            'edit' => Pages\EditExchanges::route('/{record}/edit'),
        ];
    }
}
