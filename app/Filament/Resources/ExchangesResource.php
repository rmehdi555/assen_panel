<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExchangesResource\Pages;
use App\Filament\Resources\ExchangesResource\RelationManagers;
use App\Models\Exchanges;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExchangesResource extends Resource
{
    protected static ?string $model = Exchanges::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $modelLabel = 'قیمت ارز ها';

    protected static ?string $pluralModelLabel = 'قیمت ارز';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = 'تنظیمات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)->schema([

                    Grid::make(1)->schema([
                        Grid::make(1)->schema([
                            TextInput::make('name')->label('عنوان')->columnSpan(2)->required(),
                        ]),
                        Grid::make(1)->schema([
                            TextInput::make('value')->label('قیمت (ریال)')->columnSpan(2)->numeric()->required(),
                        ]),
                    ])->columnSpan(2),
                ]),
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
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
