<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriberResource\Pages;
use App\Models\Subscriber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;


class SubscriberResource extends Resource
{
    protected static ?string $model = Subscriber::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Subscribers';
    protected static ?string $navigationGroup = 'Leads';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()->label('')->tooltip('Delete'),
            ])
            ->bulkActions([
                FilamentExportBulkAction::make('export')
                    ->fileName('subscriber-emails')
                    ->defaultFormat('pdf')     // default is PDF
                    // ->disableXlsx()         // uncomment if you don’t want Excel
                    // ->disableCsv()          // uncomment if you don’t want CSV
                    ->directDownload(),

    Tables\Actions\DeleteBulkAction::make(),

    Tables\Actions\BulkAction::make('exportEmails')
        ->label('Export Emails')
        ->icon('heroicon-o-arrow-down-tray')
        ->action(function ($records) {
            $emails = $records->pluck('email')->implode("\n");

            $fileName = 'subscriber_emails_' . now()->format('Y_m_d_H_i_s') . '.txt';
            $path = storage_path("app/{$fileName}");

            file_put_contents($path, $emails);

            return response()->download($path)->deleteFileAfterSend(true);
        })
        ->requiresConfirmation()
        ->deselectRecordsAfterCompletion()
        ->color('success'),
    ]);

    }

    public static function getRelations(): array
    {
        return [];
    }
        public static function shouldRegisterNavigation(): bool
{
    $user = auth()->user();
    return $user && ($user->isAdmin() || $user->isDeveloperRole());
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscribers::route('/'),
        ];
    }
}
