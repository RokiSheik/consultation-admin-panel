<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PdfFormSubmissionResource\Pages;
use App\Filament\Resources\PdfFormSubmissionResource\RelationManagers;
use App\Models\PdfFormSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ViewAction;
// use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;


use Illuminate\Database\Eloquent\SoftDeletingScope;

class PdfFormSubmissionResource extends Resource
{
    protected static ?string $model = PdfFormSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Leads';
    protected static ?string $navigationLabel = "PDF Book Form Submissions";


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')->required()->maxLength(255),
            TextInput::make('last_name')->required()->maxLength(255),
            TextInput::make('email')->email()->required()->maxLength(255),
            TextInput::make('phone_number')->required()->maxLength(20),
            TextInput::make('whatsapp_number')->required()->maxLength(20)->label('WhatsApp Number'),
            TextInput::make('profession')->required()->maxLength(255),
            TextInput::make('city')->required()->maxLength(255),
            Textarea::make('message')->required()->rows(6),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(),
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('first_name')->searchable()->sortable()->wrap(),
                TextColumn::make('last_name')->searchable()->sortable()->wrap(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('phone_number'),
                TextColumn::make('profession')->toggleable(),
                TextColumn::make('city')->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                ViewAction::make(),
            ])
            ->bulkActions([
                FilamentExportBulkAction::make('export')
                    ->fileName('pdf-leads')
                    ->defaultFormat('pdf')     // default is PDF
                    // ->disableXlsx()         // uncomment if you don’t want Excel
                    // ->disableCsv()          // uncomment if you don’t want CSV
                    ->directDownload(), 
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ExportBulkAction::make(),
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
            'index' => Pages\ListPdfFormSubmissions::route('/'),
            'create' => Pages\CreatePdfFormSubmission::route('/create'),
            'edit' => Pages\EditPdfFormSubmission::route('/{record}/edit'),
        ];
    }
}
