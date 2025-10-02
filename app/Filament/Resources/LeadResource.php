<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Filament\Resources\LeadResource\RelationManagers;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Leads';
    public static function getNavigationLabel(): string
    {
        return 'Campaign Leads'; // <-- custom menu text
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('landing_title')->required(),
                Forms\Components\TextInput::make('whatsapp')->required(),
                Forms\Components\TextInput::make('profession'),
                Forms\Components\TextInput::make('business_type'),
                Forms\Components\TextInput::make('current_revenue'),
                Forms\Components\TextInput::make('future_revenue'),
                Forms\Components\TextInput::make('total_team')->numeric(),
                Forms\Components\DateTimePicker::make('created_at')->disabled(),
                Forms\Components\Section::make('Other Submitted Fields')
    ->schema([
        Forms\Components\KeyValue::make('form_response')
            ->keyLabel('Field')
            ->valueLabel('Value')
            ->disabled()       // Makes it read-only
            ->columnSpanFull(),
    ])
    ->columns(1),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('id')->sortable()->label('Lead ID'),
                // Tables\Columns\TextColumn::make('landing_title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('whatsapp'),
                Tables\Columns\TextColumn::make('profession'),
                Tables\Columns\TextColumn::make('business_type'),
                Tables\Columns\TextColumn::make('current_revenue'),
                Tables\Columns\TextColumn::make('future_revenue'),
                Tables\Columns\TextColumn::make('total_team'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y h:i A')
                    ->sortable(),

            ])
            ->filters([
                Tables\Filters\Filter::make('created_today')
                    ->query(fn ($query) => $query->whereDate('created_at', today()))
                    ->label('Created Today'),
                Tables\Filters\SelectFilter::make('landing_title')
                ->label('Landing Page')
                ->options(fn () => \App\Models\Lead::query()
                    ->whereNotNull('landing_title')            // remove nulls
                    ->where('landing_title', '!=', '')         // remove empty strings
                    ->distinct()
                    ->pluck('landing_title', 'landing_title')  // key => label
                    ->filter()                                 // drop any nullish
                    ->mapWithKeys(fn($title) => [(string)$title => (string)$title])
                    ->toArray()
                ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}
