<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FactoryResource\Pages;
use App\Filament\Resources\FactoryResource\RelationManagers;
use App\Models\Factories;
use App\Models\Factory;
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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class FactoryResource extends Resource
{
    protected static ?string $model = Factories::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $modelLabel = 'کارخانه ها';

    protected static ?string $pluralModelLabel = 'کارخانه';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'محصولات';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(3)->schema([
                Grid::make(1)->schema([
                    Grid::make(1)->schema([
                        TextInput::make('title')->required()->label('نام')->maxLength(255),
                    ]),

                    Section::make()->schema([
                        TinyEditor::make('body')->label('متن')->fileAttachmentsDisk('public')->fileAttachmentsVisibility('public')->fileAttachmentsDirectory('uploads')->required()->maxHeight(500),
                    ]),

                    Section::make('سئو')->schema([
                        TextInput::make('seo_title')->label('تایتل صفحه')->maxLength(255),
                        Textarea::make('seo_description')->label('توضیحات صفحه')->maxLength(65535),
                        Toggle::make('seo_follow')->label('follow'),
                        Toggle::make('seo_index')->label('index'),
                        TextInput::make('seo_canonical')->label('canonical'),
                    ])->collapsed(),

                ])->columnSpan(2),

                Section::make()->schema([
                    TextInput::make('slug')->label('اسلاگ')->unique(ignoreRecord: true)->maxLength(255)->required(),
                    Select::make('product_categories_id')->relationship('category', 'title')->label('دسته بندی')->required(),
                    TextInput::make('priority')->label('اولویت نمایش')->numeric()->required(),
                    FileUpload::make('image_name')->image()->label('تصویر')->imageEditor()->required(),
                    Toggle::make('is_show')->label('وضعیت نمایش')->required(),
                ])->columnSpan(1),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('نام'),
                TextColumn::make('slug')->label('اسلاگ'),
                TextColumn::make('priority')->label('اولویت نمایش')->sortable(),
                IconColumn::make('is_show')->label('وضعیت نمایش')->boolean(),
            ])
            ->filters([

                Filter::make('title')->form([
                    TextInput::make('title')->label('نام'),
                ])->query(fn(Builder $query, array $data): Builder => $query->when(
                    $data['title'],
                    fn(Builder $query, $data): Builder => $query->where('title', 'like', '%' . $data . '%')
                )),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFactories::route('/'),
            'create' => Pages\CreateFactory::route('/create'),
            'edit' => Pages\EditFactory::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('factory::view');
    }
}
