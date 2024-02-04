<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Filament\Resources\SliderResource\RelationManagers;
use App\Models\Sliders;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class SliderResource extends Resource
{
    protected static ?string $model = Sliders::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'اسلایدر';

    protected static ?string $pluralModelLabel = 'اسلایدر';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationGroup = 'تنظیمات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)->schema([

                    Grid::make(1)->schema([
                        Grid::make(1)->schema([
                            TextInput::make('title')->label('عنوان')->columnSpan(2)->required(),
                        ]),
                        Grid::make(1)->schema([
                            TextInput::make('link')->label('لینک')->columnSpan(2)->required(),
                        ]),
                    ])->columnSpan(2),

                    Section::make()->schema([
                        FileUpload::make('image_name')->image()->label('تصویر سایز مناسب : 480*1440')->imageEditor()->required(),
                        Toggle::make('is_show')->label('وضعیت نمایش')->required(),
                        Toggle::make('target')->label('لینک خارج از سایت')->required(),

                    ])->columnSpan(1),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('عنوان')->searchable(),
                TextColumn::make('link')->label('لینک'),
                IconColumn::make('is_show')->label('وضعیت نمایش')->boolean(),
                TextColumn::make('created_at')->label('ایجاد در')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->defaultSort('updated_at', 'desc');
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('slider::view');
    }
}
