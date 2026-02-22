<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleCategoryResource\Pages;
use App\Models\ArticleCategory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
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
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;

class ArticleCategoryResource extends Resource
{
    protected static ?string $model = ArticleCategory::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $modelLabel = 'دسته بندی مقالات';

    protected static ?string $pluralModelLabel = 'دسته بندی مقالات';

    protected static ?int $navigationSort = 2;

    protected static \UnitEnum|string|null $navigationGroup = 'محتوا';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(3)->schema([
            Section::make()->schema([
                TextInput::make('title')->required()->label('نام')->maxLength(255),
                Textarea::make('description')->label('خلاصه')->maxLength(65535)->required(),
                TinyEditor::make('body')->label('متن')->fileAttachmentsDisk('public')->fileAttachmentsVisibility('public')->fileAttachmentsDirectory('uploads')->required(),
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
                FileUpload::make('image_name')->image()->label('تصویر')->imageEditor()->required(),
                Toggle::make('is_show')->label('وضعیت نمایش')->required(),
            ])->columnSpan(1)->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('نام'),
                TextColumn::make('slug')->label('اسلاگ'),
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
                ActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticleCategories::route('/'),
            'create' => Pages\CreateArticleCategory::route('/create'),
            'edit' => Pages\EditArticleCategory::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('article-categories::view');
    }
}
