<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlaylistVideoResource\Pages;
use App\Filament\Resources\PlaylistVideoResource\RelationManagers;
use App\Models\PlaylistVideo;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlaylistVideoResource extends Resource
{
    protected static ?string $model = PlaylistVideo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = "Courses";
    // protected static ?string $modelLabel = "All Customers";
    protected static ?string $navigationLabel = "Videos";
 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required(),

                TextInput::make('title')->required(),
                TextInput::make('video_url')->label('Video URL')->required(),
                TextInput::make('order')->numeric()->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(),
                TextColumn::make('id'),
                TextColumn::make('course.title')->label('Course'),
                TextColumn::make('title')->limit(40),
                // TextColumn::make('video_url')->limit(40),
                TextColumn::make('order')->sortable(),
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
            'index' => Pages\ListPlaylistVideos::route('/'),
            'create' => Pages\CreatePlaylistVideo::route('/create'),
            'edit' => Pages\EditPlaylistVideo::route('/{record}/edit'),
        ];
    }
}
