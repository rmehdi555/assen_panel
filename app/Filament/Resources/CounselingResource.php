<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CounselingResource\Pages;
use App\Filament\Resources\CounselingResource\RelationManagers;
use App\Models\Counseling;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CounselingResource extends Resource
{
    protected static ?string $model = Counseling::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'پیام درخواست مشاوره';

    protected static ?string $pluralModelLabel = 'پیام های درخواست مشاوره';

    protected static ?string $slug = 'counseling';

    protected static ?string $navigationGroup = 'مدیریت کاربران';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('نام'),
                TextColumn::make('phone')->label('شماره همراه'),
                TextColumn::make('created_at')->label('تاریخ ثبت')->jalaliDate(),
            ])
            ->defaultSort('created_at','desc')
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCounselings::route('/'),
//            'create' => Pages\CreateCounseling::route('/create'),
//            'edit' => Pages\EditCounseling::route('/{record}/edit'),
        ];
    }
}
