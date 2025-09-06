<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PodcastRequestResource\Pages;
use App\Filament\Resources\PodcastRequestResource\RelationManagers;
use App\Models\PodcastRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;


class PodcastRequestResource extends Resource
{
    protected static ?string $model = PodcastRequest::class;
    protected static ?string $navigationGroup = 'Leads';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('podcast_name')->required(),
                Forms\Components\TextInput::make('full_name')->required(),
                Forms\Components\TextInput::make('phone_number')->tel(),
                Forms\Components\TextInput::make('whatsapp_number')->tel(),
                Forms\Components\TextInput::make('email')->email(),
                Forms\Components\TextInput::make('website_url')->url(),
                Forms\Components\TextInput::make('social_media_links'),
                Forms\Components\Textarea::make('podcast_description'),
                Forms\Components\TextInput::make('request_speaker'),
                Forms\Components\Select::make('interview_mode')
                    ->options([
                        'online' => 'Online',
                        'offline' => 'Offline',
                    ]),
                Forms\Components\Textarea::make('reason_for_guest'),
                Forms\Components\TextInput::make('interview_length'),
                Forms\Components\Textarea::make('talking_points'),
                Forms\Components\TextInput::make('average_views'),
                Forms\Components\Toggle::make('media_presence_agreement'),
                Forms\Components\Toggle::make('share_raw_footage_agreement'),
                Forms\Components\Toggle::make('final_approval_agreement'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('podcast_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('full_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phone_number'),
                Tables\Columns\TextColumn::make('interview_mode'),
                // Tables\Columns\IconColumn::make('media_presence_agreement')->boolean(),
                // Tables\Columns\IconColumn::make('share_raw_footage_agreement')->boolean(),
                // Tables\Columns\IconColumn::make('final_approval_agreement')->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                FilamentExportBulkAction::make('export')
                    ->fileName('podcast-requests')
                    ->defaultFormat('pdf')
                    ->directDownload(), 
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
        public static function shouldRegisterNavigation(): bool
{
    $user = auth()->user();
    return $user && ($user->isAdmin() || $user->isDeveloperRole());
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPodcastRequests::route('/'),
            'create' => Pages\CreatePodcastRequest::route('/create'),
            'edit' => Pages\EditPodcastRequest::route('/{record}/edit'),
        ];
    }
}
