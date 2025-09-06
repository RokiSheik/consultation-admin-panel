<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpeakingRequestResource\Pages;
use App\Filament\Resources\SpeakingRequestResource\RelationManagers;
use App\Models\SpeakingRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;


class SpeakingRequestResource extends Resource
{
    protected static ?string $model = SpeakingRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Leads';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('event_name')->required(),
                Forms\Components\TextInput::make('first_name')->required(),
                Forms\Components\TextInput::make('last_name')->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('phone_number')->required(),
                Forms\Components\TextInput::make('whatsapp_number')->required(),
                Forms\Components\Textarea::make('business_description')->required(),
                Forms\Components\Textarea::make('speaker_request')->required(),
                Forms\Components\DatePicker::make('speaking_date')->required(),
                Forms\Components\TextInput::make('event_time')->required(),
                Forms\Components\TextInput::make('speaking_location')->required(),
                Forms\Components\TextInput::make('speaking_length')->required(),
                Forms\Components\Textarea::make('talking_points'),
                Forms\Components\TextInput::make('keynote_speaker'),
                Forms\Components\TextInput::make('audience_size'),
                Forms\Components\CheckboxList::make('advertise_event')
                    ->options([
                        'Yes' => 'Yes',
                        'No' => 'No',
                    ]),
                Forms\Components\Textarea::make('social_media_links'),
                Forms\Components\Textarea::make('event_content_distribution'),
                Forms\Components\TextInput::make('hotel_flights_agreement'),
                Forms\Components\TextInput::make('credentials_agreement'),
                Forms\Components\TextInput::make('powerpoint_access'),
                Forms\Components\TextInput::make('raw_footage_agreement'),
                Forms\Components\TextInput::make('media_presence_agreement'),
                Forms\Components\TextInput::make('content_approval_agreement'),
                Forms\Components\Textarea::make('other_engagements'),
                Forms\Components\TextInput::make('fee_understanding'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('event_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('first_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('last_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phone_number'),
                Tables\Columns\TextColumn::make('speaking_date')->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                FilamentExportBulkAction::make('export')
                    ->fileName('speaking-requests')
                    ->defaultFormat('pdf')     // default is PDF
                    // ->disableXlsx()         // uncomment if you don’t want Excel
                    // ->disableCsv()          // uncomment if you don’t want CSV
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
            'index' => Pages\ListSpeakingRequests::route('/'),
            'create' => Pages\CreateSpeakingRequest::route('/create'),
            'edit' => Pages\EditSpeakingRequest::route('/{record}/edit'),
        ];
    }
}
