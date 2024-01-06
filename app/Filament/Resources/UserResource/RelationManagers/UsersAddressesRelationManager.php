<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersAddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    protected static ?string $label = 'آدرس';

    protected static ?string $pluralLabel = 'آدرس ها';

    protected static ?string $title = 'آدرس های کاربر';

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(1)->schema([
                TextInput::make('content')->required()->label('آدرس'),
                TextInput::make('postalcode')->required()->label('کد پستی'),
                Select::make('state_id')->relationship(name: 'state', titleAttribute: 'title')->label('استان')->searchable()->required()->preload(),
                Select::make('city_id')->relationship(name: 'city', titleAttribute: 'title')->label('شهر')->searchable()->required()->preload(),
            ])
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->columns([
                TextColumn::make('id')->label('آیدی'),
                TextColumn::make('state.title')->label('استان'),
                TextColumn::make('city.title')->label('شهر'),
                TextColumn::make('content')->label('آدرس'),
                TextColumn::make('postalcode')->label('کدپستی'),
            ])
            ->filters([])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([])
            ->emptyStateActions([
                CreateAction::make(),
            ]);
    }
}
