<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Filament\Resources\CommentResource\RelationManagers;
use App\Models\Article;
use App\Models\Comment;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    protected static ?string $modelLabel = 'نظر';

    protected static ?string $pluralModelLabel = 'نظرات';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'محتوا';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)->schema([
                    Grid::make(1)->schema([
                        TextInput::make('phone')->label('شماره تماس (اختیاری)')->columnSpan(2),
                    ]),

                    Grid::make(1)->schema([
                        Section::make()->schema([
                            Textarea::make('comment')->label('نظر')->maxLength(65535)->required(),
                        ]),

                    ])->columnSpan(2),

                    Section::make()->schema([
                        Toggle::make('is_show')->label('وضعیت نمایش')->required(),
                    ])->columnSpan(1),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('phone')->label('شماره تماس')->searchable(),
                TextColumn::make('comment')->label('نظر')->searchable(),
                TextColumn::make('link_view_article')->label('نمایش در سایت ')
                    ->url(fn(Comment $comment) => config('app.front_url') . "/articles/" . "{$comment->type_slug}")
                    ->getStateUsing(fn(Comment $comment) => "{$comment->type_slug}")
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-link')
                    ->color('primary'),
                TextColumn::make('rate')->label('رتبه'),
                IconColumn::make('is_show')->label('وضعیت نمایش')->boolean(),
                TextColumn::make('created_at')->label('ایجاد در')->dateTime(),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
            ])->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}
