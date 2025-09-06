<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentResource\Pages;
use App\Filament\Resources\ContentResource\RelationManagers;
use App\Models\Content;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContentResource extends Resource
{
    protected static ?string $model = Content::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required(),
                // TextInput::make('text')->label('Author Text'),
                TextInput::make('author'),
                FileUpload::make('image')->directory('contents'),
                FileUpload::make('large_image')->directory('contents'),
                TextInput::make('video_url'),

                TagsInput::make('categories')->splitKeys([',']),
                TagsInput::make('tags')->splitKeys([',']),
                // Textarea::make('excerpt'),
                DatePicker::make('date'),
                // TextInput::make('view'),
                Repeater::make('body')
                    ->schema([
                        Textarea::make('value')->label('Paragraph'),
                    ])
                    ->label('Body Paragraphs')
                    ->defaultItems(1)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(),
                TextColumn::make('title')->searchable()->sortable(),
                ImageColumn::make('image')->label('Thumbnail')->circular(),
                TextColumn::make('view'),
                TextColumn::make('date'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('')->tooltip('View'),
                Tables\Actions\EditAction::make()->label('')->tooltip('Edit'),
                Tables\Actions\DeleteAction::make()->label('')->tooltip('Delete')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListContents::route('/'),
            'create' => Pages\CreateContent::route('/create'),
            'edit' => Pages\EditContent::route('/{record}/edit'),
        ];
    }
}
