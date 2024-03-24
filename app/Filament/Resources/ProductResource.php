<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Factories;
use App\Models\Invoice;
use App\Models\InvoiceDescription;
use App\Models\Logistic;
use App\Models\Path;
use App\Models\Product;
use App\Models\Products;
use App\Models\Sizes;
use App\Models\Standards;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Actions;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use Ramsey\Collection\Collection;

class ProductResource extends Resource
{
    protected static ?string $model = Products::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';


    protected static ?string $modelLabel = 'محصول';

    protected static ?string $pluralModelLabel = 'محصولات';

    protected static ?int $navigationSort = 1;

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
                        Textarea::make('description')->label('خلاصه')->maxLength(65535)->required(),
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
                    TextInput::make('price')->label('قیمت ( ریال)')->required()->minValue(0)->numeric(),
                    TextInput::make('price_usd')->label('قیمت ( دلار)')->required()->minValue(0)->default(0)->numeric(),
                    TextInput::make('price_euro')->label('قیمت ( یورو)')->required()->minValue(0)->default(0)->numeric(),
                    Select::make('product_categories_id')->relationship('category', 'title')->label('دسته بندی')->preload()->live()->required(),
                    Select::make('factory_id')->options(fn(GET $get) => Factories::query()->where('product_categories_id', $get('product_categories_id'))->pluck('title', 'id'))->label(' کارخانه')->required(),

                    Select::make('size_id')->options(fn(GET $get) => Sizes::query()->where('product_categories_id', $get('product_categories_id'))->pluck('title', 'id'))->label(' سایز')->required(),
                    Select::make('standard_id')->options(fn(GET $get) => Standards::query()->where('product_categories_id', $get('product_categories_id'))->pluck('title', 'id'))->label('استاندارد ')->required(),
                    Select::make('place_of_delivery')->label('محل تحویل')
                        ->options([
                            'store' => 'انبار',
                            'factory' => 'کارخانه',
                        ])->default('factory'),
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
//                TextColumn::make('slug')->label('اسلاگ'),
                TextColumn::make('category.title')->label('دسته بندی'),
                TextColumn::make('factoryDetails.title')->label('کارخانه'),
                TextColumn::make('sizeDetails.title')->label('سایز'),
                TextColumn::make('standardDetails.title')->label('استاندارد'),
                TextColumn::make('price')->label('قیمت'),
                TextColumn::make('priority')->label('اولویت نمایش')->sortable(),
                TextColumn::make('updated_at')->label('تاریخ بروزرسانی')->jalaliDate(),
                IconColumn::make('is_show')->label('وضعیت نمایش')->boolean(),
            ])
            ->filters([
                Filter::make('title')->form([
                    TextInput::make('title')->label('نام'),
                ])->query(fn(Builder $query, array $data): Builder => $query->when(
                    $data['title'],
                    fn(Builder $query, $data): Builder => $query->where('title', 'like', '%' . $data . '%')
                )),
                SelectFilter::make('category')->label('دسته بندی')->relationship('category', 'title'),
                SelectFilter::make('factory')->label('کارخانه')->relationship('factoryDetails', 'title'),
                SelectFilter::make('size')->label('سایز')->relationship('sizeDetails', 'title'),
                SelectFilter::make('standard')->label('استاندارد')->relationship('standardDetails', 'title'),
                Filter::make('is_show')->label('وضعیت نمایش')->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Action::make('price-update')->form([
                    TextInput::make('price')->label('قیمت ( ریال)')->required()->minValue(0)->numeric()->default(fn(Products $record)=> $record->price),
                ])->label('برروزرسانی قیمت')->action(function (Products $record, array $data) {
                    self::updateRecord($record, $data);
                    return $record;
                }),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),

                BulkAction::make('edition')->label('بروزرسانی قیمت گروهی')
                    ->form([
                        TextInput::make('price')->label('قیمت ( ریال)')->required()->minValue(0)->numeric(),
                    ])
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data) {
                        foreach ($records as $record) {
                            self::updateRecord($record, $data);
                        }
                        return $records;
                    })
            ])->defaultPaginationPageOption(25);
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }


    private static function updateRecord($record, $data)
    {
        $record->update([
            'price_old' => $record->price,
            'price' => $data['price'],
        ]);
        return $record;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('product::view');
    }
}
