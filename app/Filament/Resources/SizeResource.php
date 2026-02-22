<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SizeResource\Pages;
use App\Filament\Resources\SizeResource\RelationManagers;
use App\Models\Size;
use App\Models\Sizes;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;

class SizeResource extends Resource
{
    protected static ?string $model = Sizes::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'سایز ها';

    protected static ?string $pluralModelLabel = 'سایز';

    protected static ?int $navigationSort = 4;

    protected static \UnitEnum|string|null $navigationGroup = 'محصولات';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(3)->schema([
            Section::make()->schema([
                TextInput::make('title')->required()->label('عنوان')->maxLength(255),
                TextInput::make('title_h1')->required()->label('عنوان h1 :')->maxLength(255),
                TinyEditor::make('body')->label('متن')->fileAttachmentsDisk('public')->fileAttachmentsVisibility('public')->fileAttachmentsDirectory('uploads')->required()->maxHeight(500),
                Section::make('سئو')->schema([
                    TextInput::make('seo_title')->label('تایتل صفحه')->maxLength(255),
                    Textarea::make('seo_description')->label('توضیحات صفحه')->maxLength(65535),
                    Toggle::make('seo_follow')->label('follow'),
                    Toggle::make('seo_index')->label('index'),
                    TextInput::make('seo_canonical')->label('canonical'),
                ])->collapsed(),
            ])->columnSpan(2)->columns(1),
            Section::make()->schema([
                TextInput::make('slug')->label('اسلاگ')->unique(ignoreRecord: true)->maxLength(255)->required(),
                Select::make('product_categories_id')->relationship('category', 'title')->label('دسته بندی')->required(),
                TextInput::make('priority')->label('اولویت نمایش')->numeric()->required(),
                FileUpload::make('image_name')->image()->label('تصویر')->imageEditor()->required(),
                Toggle::make('is_show')->label('وضعیت نمایش')->required(),
            ])->columnSpan(1)->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('عنوان'),
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
                EditAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ])
            ->emptyStateActions([
                CreateAction::make(),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSizes::route('/'),
            'create' => Pages\CreateSize::route('/create'),
            'edit' => Pages\EditSize::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('size::view');
    }
}
